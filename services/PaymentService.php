<?php

namespace App\services;

use App\core\Application;
use App\models\User;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class PaymentService
{
    private string $apiKey;
    private string $webhookSecret;
    private string $currency = 'mad';
    
    public function __construct()
    {
        $this->apiKey = $_ENV['STRIPE_SECRET_KEY'] ?? '';
        $this->webhookSecret = $_ENV['STRIPE_WEBHOOK_SECRET'] ?? '';
        
        // Initialize Stripe
        Stripe::setApiKey($this->apiKey);
    }
    
    /**
     * Create a Stripe Checkout Session for the cart items
     *
     * @param array $cartItems Array of cart items
     * @param User $user User making the purchase
     * @param string $successUrl URL to redirect on successful payment
     * @param string $cancelUrl URL to redirect on cancelled payment
     * @return string The Stripe Checkout URL
     * @throws \Exception
     */
    public function createCheckoutSession(array $cartItems, User $user, string $successUrl, string $cancelUrl): string
    {
        if (empty($cartItems)) {
            throw new \Exception('Cart is empty');
        }
        
        try {
            // Format line items for Stripe
            $lineItems = [];
            foreach ($cartItems as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => $this->currency,
                        'product_data' => [
                            'name' => $item->name,
                            'description' => substr("Product ID: {$item->product_id}", 0, 255),
                            'images' => [$item->image_path ? Application::$app->request->getHostInfo() . $item->image_path : null],
                        ],
                        'unit_amount' => (int)($item->price), 
                    ],
                    'quantity' => $item->quantity,
                ];
            }
            
            // Create Checkout Session
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'customer_email' => $user->email,
                'client_reference_id' => $user->id,
                'metadata' => [
                    'user_id' => $user->id,
                ],
                'shipping_address_collection' => [
                    'allowed_countries' => ['US', 'CA', 'MA'],
                ],
            ]);
            
            return $session->url;
            
        } catch (ApiErrorException $e) {
            Application::$app->session->setFlash('error', 'Payment Error: ' . $e->getMessage());
            throw new \Exception('Failed to create checkout session: ' . $e->getMessage());
        }
    }
    
    /**
     * Handle Stripe webhook events
     *
     * @param string $payload The raw payload from Stripe
     * @param string $sigHeader The Stripe signature header
     * @return bool 
     */
    public function handleWebhook(string $payload, string $sigHeader): bool
    {
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $this->webhookSecret
            );
            
            // Handle the event
            switch ($event->type) {
                case 'checkout.session.completed':
                    return $this->handleCheckoutSessionCompleted($event->data->object);
                
                case 'payment_intent.succeeded':
                    // Payment was successful
                    return $this->handlePaymentIntentSucceeded($event->data->object);
                
                case 'payment_intent.payment_failed':
                    // Payment failed
                    return $this->handlePaymentIntentFailed($event->data->object);
                
                default:
                    // Unexpected event type
                    return true;
            }
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Application::$app->session->setFlash('error', 'Webhook Error: ' . $e->getMessage());
            return false;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Application::$app->session->setFlash('error', 'Webhook Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Handle checkout.session.completed event
     *
     * @param \Stripe\Checkout\Session $session
     * @return bool
     */
    private function handleCheckoutSessionCompleted($session): bool
    {
        // Get user ID from metadata
        $userId = $session->metadata->user_id ?? null;
        
        if (!$userId) {
            return false;
        }
        
        // Create order in database
        $orderService = new OrderService();
        $orderResult = $orderService->createOrderFromCheckout(
            $userId,
            $session->id,
            $session->amount_total / 100, // Convert from cents
            $session->customer_details->address ?? null
        );
        
        return $orderResult;
    }
    
    /**
     * Handle payment_intent.succeeded event
     *
     * @param \Stripe\PaymentIntent $paymentIntent
     * @return bool
     */
    private function handlePaymentIntentSucceeded($paymentIntent): bool
    {
        // Update order status
        $orderService = new OrderService();
        return $orderService->updateOrderStatus($paymentIntent->id, 'paid');
    }
    
    /**
     * Handle payment_intent.payment_failed event
     *
     * @param \Stripe\PaymentIntent $paymentIntent
     * @return bool
     */
    private function handlePaymentIntentFailed($paymentIntent): bool
    {
        // Update order status
        $orderService = new OrderService();
        return $orderService->updateOrderStatus($paymentIntent->id, 'failed');
    }
}