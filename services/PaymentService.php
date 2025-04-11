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


    public function processRefund(string $paymentIntentId, float $amount = null): bool
    {
        try {
            error_log("Starting refund process for: " . $paymentIntentId);
            
            // Initialize Stripe
            Stripe::setApiKey($this->apiKey);
            
            // Check if this is a checkout session ID
            if (strpos($paymentIntentId, 'cs_') === 0) {
                error_log("Detected checkout session ID, retrieving session");
                try {
                    $session = \Stripe\Checkout\Session::retrieve($paymentIntentId);
                    $paymentIntentId = $session->payment_intent;
                    error_log("Retrieved payment intent ID: " . $paymentIntentId);
                } catch (\Exception $e) {
                    error_log("Error retrieving checkout session: " . $e->getMessage());
                    return false;
                }
            }
            
            // Make sure we have a payment intent ID
            if (empty($paymentIntentId)) {
                error_log("Cannot process refund: No payment intent ID found");
                return false;
            }
            
            // Create the refund
            $refundParams = [
                'payment_intent' => $paymentIntentId,
                'reason' => 'requested_by_customer',
            ];
            
            // Only specify amount if provided
            if ($amount !== null) {
                $refundParams['amount'] = (int)($amount * 100); // Convert to cents
                error_log("Refunding specific amount: " . (int)($amount * 100) . " cents");
            } else {
                error_log("Refunding full amount");
            }
            
            $refund = \Stripe\Refund::create($refundParams);
            
            error_log("Refund processed successfully: " . $refund->id);
            return true;
        } catch (\Exception $e) {
            error_log("Error processing refund: " . $e->getMessage());
            if ($e instanceof \Stripe\Exception\ApiErrorException) {
                error_log("Stripe error details: " . json_encode($e->getJsonBody()));
            }
            return false;
        }
    }
public function testRefund(): bool
{
    try {
        // Initialize Stripe
        Stripe::setApiKey($this->apiKey);
        
        // Use a real checkout session ID from your order
        $checkoutSessionId = "cs_test_a1Ij1OjmkSPbEXhcRG9eM0IV2aEccvgb1LjYm9rMozL7kZZbgLb788h7eR";
        
        error_log("Retrieving session: " . $checkoutSessionId);
        
        // Retrieve the checkout session to get its payment intent
        $session = \Stripe\Checkout\Session::retrieve($checkoutSessionId);
        
        // Get the payment intent ID from the session
        $paymentIntentId = $session->payment_intent;
        error_log("Got payment intent ID: " . $paymentIntentId);
        
        if (empty($paymentIntentId)) {
            error_log("No payment intent found in checkout session");
            return false;
        }
        
        // Create the refund using the payment intent ID
        error_log("Creating refund for payment intent: " . $paymentIntentId);
        $refund = \Stripe\Refund::create([
            'payment_intent' => $paymentIntentId,
            'reason' => 'requested_by_customer',
        ]);
        
        error_log("Refund created: " . $refund->id);
        return true;
    } catch (\Exception $e) {
        error_log("Error in test refund: " . $e->getMessage());
        if ($e instanceof \Stripe\Exception\ApiErrorException) {
            error_log("Stripe error details: " . json_encode($e->getJsonBody()));
        }
        return false;
    }
}
}
