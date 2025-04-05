<?php

namespace App\services;

use App\core\Application;
use App\models\User;
use App\repositories\OrderRepository;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class PaymentService
{
    private string $apiKey;
    private string $webhookSecret;
    private string $currency = 'mad';
    private OrderRepository $orderRepository;
    
    public function __construct()
    {
        $this->apiKey = $_ENV['STRIPE_SECRET_KEY'] ?? '';
        $this->webhookSecret = $_ENV['STRIPE_WEBHOOK_SECRET'] ?? '';
        $this->orderRepository = new OrderRepository();
        Stripe::setApiKey($this->apiKey);
    }

    public function createCheckoutSession(array $cartItems, User $user, string $successUrl, string $cancelUrl): string
    {
        if (empty($cartItems)) {
            throw new \Exception('Cart is empty');
        }
        
        try {
            $lineItems = [];
            foreach ($cartItems as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => $this->currency,
                        'product_data' => [
                            'name' => $item->name,
                            'description' => substr("Product ID: {$item->product_id}", 0, 255),
                        ],
                        'unit_amount' => (int)($item->price * 100), 
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            $this->logDebug('Creating Stripe Checkout Session with data: ' . json_encode([
                'payment_method_types' => ['card'],
                'line_items_count' => count($lineItems),
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'customer_email' => $user->email,
                'client_reference_id' => $user->id,
            ]));
            
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += $item->price * $item->quantity;
            }

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'customer_email' => $user->email,
                'client_reference_id' => (string)$user->id,
                'metadata' => [
                    'user_id' => (string)$user->id,
                    'total_amount' => (string)$totalAmount,
                ],
                'shipping_address_collection' => [
                    'allowed_countries' => ['US', 'CA', 'MA'],
                ],
            ]);

            $this->logDebug('Checkout session created successfully: ' . $session->id);
            $this->preCreateOrder($user->id, $session->id, $totalAmount);
            return $session->url;
            
        } catch (ApiErrorException $e) {
            $this->logError('Stripe Error: ' . $e->getMessage());
            $this->logError('Error Details: ' . json_encode($e->getJsonBody()));
            Application::$app->session->setFlash('error', 'Payment Error: ' . $e->getMessage());
            throw new \Exception('Failed to create checkout session: ' . $e->getMessage());
        }
    }

    private function preCreateOrder(int $userId, string $sessionId, float $totalAmount): ?int
    {
        $existingOrder = $this->orderRepository->findByPaymentIntentId($sessionId);
        if ($existingOrder) {
            $this->logDebug("Order already exists for session ID: {$sessionId}");
            return $existingOrder['id'];
        }

        $orderData = [
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_intent_id' => $sessionId,
            'payment_method' => 'stripe',
            'shipping_address' => 'To be updated by webhook'
        ];

        $orderId = $this->orderRepository->create($orderData);

        if ($orderId) {
            $this->logDebug("Pre-created order ID: {$orderId} for session: {$sessionId}");

            $cartService = new CartService();
            $cartItems = $cartService->getCartItems();

            $orderItemService = new OrderService();
            $orderItemService->addItemsToOrder($orderId, $cartItems);

            return $orderId;
        } else {
            $this->logError("Failed to pre-create order for session: {$sessionId}");
            return null;
        }
    }

    public function handleWebhook(string $payload, string $sigHeader): bool
    {
        try {
            $this->logDebug("Received webhook payload: " . substr($payload, 0, 500) . "...");
            $this->logDebug("Signature header: " . $sigHeader);

            if (empty($this->webhookSecret)) {
                $this->logError("Webhook secret not configured");
                return false;
            }

            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $this->webhookSecret
            );

            $this->logDebug("Webhook event type: " . $event->type);

            switch ($event->type) {
                case 'checkout.session.completed':
                    $result = $this->handleCheckoutSessionCompleted($event->data->object);
                    $this->logDebug("Handled checkout.session.completed: " . ($result ? 'Success' : 'Failed'));
                    return $result;

                case 'payment_intent.succeeded':
                    $result = $this->handlePaymentIntentSucceeded($event->data->object);
                    $this->logDebug("Handled payment_intent.succeeded: " . ($result ? 'Success' : 'Failed'));
                    return $result;

                case 'payment_intent.payment_failed':
                    $result = $this->handlePaymentIntentFailed($event->data->object);
                    $this->logDebug("Handled payment_intent.payment_failed: " . ($result ? 'Success' : 'Failed'));
                    return $result;

                default:
                    $this->logDebug("Ignored unhandled event type: " . $event->type);
                    return true;
            }
        } catch (\UnexpectedValueException $e) {
            $this->logError('Webhook Error (Invalid Payload): ' . $e->getMessage());
            return false;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            $this->logError('Webhook Error (Invalid Signature): ' . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            $this->logError('Webhook Error (General): ' . $e->getMessage());
            $this->logError('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    private function handleCheckoutSessionCompleted($session): bool
    {
        $this->logDebug("Processing checkout.session.completed. Session ID: " . $session->id);
        $this->logDebug("Session metadata: " . json_encode($session->metadata->toArray()));
        $this->logDebug("Session client_reference_id: " . $session->client_reference_id);

        $userId = $session->metadata->user_id ?? $session->client_reference_id ?? null;

        if (!$userId) {
            $this->logError("No user ID found in session metadata or client_reference_id");
            return false;
        }

        $existingOrder = $this->orderRepository->findByPaymentIntentId($session->id);

        if ($existingOrder) {
            $this->logDebug("Found existing order ID: {$existingOrder['id']} for session: {$session->id}");

            $shippingAddress = null;
            if (isset($session->customer_details) && isset($session->customer_details->address)) {
                $shippingAddress = $this->formatShippingAddress($session->customer_details->address);

                $this->orderRepository->update($existingOrder['id'], [
                    'shipping_address' => $shippingAddress,
                    'status' => 'processing'
                ]);

                $this->logDebug("Updated existing order with shipping address");
            }

            if (isset($session->payment_intent) && $session->payment_intent !== $session->id) {
                $this->orderRepository->update($existingOrder['id'], [
                    'payment_intent_id' => $session->payment_intent
                ]);
                $this->logDebug("Updated order with payment_intent_id: {$session->payment_intent}");
            }

            return true;
        }

        $totalAmount = $session->amount_total / 100;

        $shippingAddress = "Not provided";
        if (isset($session->customer_details) && isset($session->customer_details->address)) {
            $shippingAddress = $this->formatShippingAddress($session->customer_details->address);
        }

        $paymentIntentId = $session->payment_intent ?? $session->id;

        $orderData = [
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'status' => 'processing',
            'payment_intent_id' => $paymentIntentId,
            'payment_method' => 'stripe',
            'shipping_address' => $shippingAddress
        ];

        $orderId = $this->orderRepository->create($orderData);

        if (!$orderId) {
            $this->logError("Failed to create order from webhook");
            return false;
        }

        $this->logDebug("Created new order ID: {$orderId} from webhook");

        return true;
    }

    private function formatShippingAddress($address): string
    {
        $addressParts = [];

        if (!empty($address->line1)) $addressParts[] = $address->line1;
        if (!empty($address->line2)) $addressParts[] = $address->line2;
        if (!empty($address->city)) $addressParts[] = $address->city;
        if (!empty($address->state)) $addressParts[] = $address->state;
        if (!empty($address->postal_code)) $addressParts[] = $address->postal_code;
        if (!empty($address->country)) $addressParts[] = $address->country;

        return implode(', ', $addressParts);
    }

    private function handlePaymentIntentSucceeded($paymentIntent): bool
    {
        $this->logDebug("Processing payment_intent.succeeded. ID: " . $paymentIntent->id);

        $order = $this->orderRepository->findByPaymentIntentId($paymentIntent->id);

        if (!$order) {
            $this->logWarning("No order found for payment intent ID: " . $paymentIntent->id);
            return false;
        }

        $this->orderRepository->update($order['id'], ['status' => 'processing']);
        $this->logDebug("Updated order ID {$order['id']} to status 'processing'");
        return true;
    }

    private function handlePaymentIntentFailed($paymentIntent): bool
    {
        $this->logDebug("Processing payment_intent.payment_failed. ID: " . $paymentIntent->id);

        $order = $this->orderRepository->findByPaymentIntentId($paymentIntent->id);

        if (!$order) {
            $this->logWarning("No order found for failed payment intent ID: " . $paymentIntent->id);
            return false;
        }

        $this->orderRepository->update($order['id'], ['status' => 'failed']);
        $this->logDebug("Updated order ID {$order['id']} to status 'failed'");
        return true;
    }

    private function logDebug(string $message): void
    {
        error_log('[DEBUG] ' . $message);
    }

    private function logError(string $message): void
    {
        error_log('[ERROR] ' . $message);
    }

    private function logWarning(string $message): void
    {
        error_log('[WARNING] ' . $message);
    }
}
