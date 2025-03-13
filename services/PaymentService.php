<?php

namespace App\services;

use App\core\Application;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class PaymentService
{
    private string $apiKey;
    private string $webhookSecret;
    private string $currency = 'mad';


    public function __construct()
    {
        $this->apiKey = $_ENV['STRIPE_SECRET_KEY'] ?? '';
        $this->webhookSecret = $_ENV['STRIPE_WEBHOOK_SECRET'] ?? '';

        Stripe::setApiKey($this->apiKey);
    }

    public function createCheckoutSession(array $cartItems, User $user, string $successUrl, string $cancelUrl)
    {
        if(empty($cartItems))
        {
            throw new \Exception('Cart is empty');
        }

        try{
            $lineItems = [];

            foreach($cartItems as $item)
            {
                $lineItems[] = [
                    'price_data' =>[
                        'currency' => $this->currency,
                        'product_data' =>[
                            'name' => $item->name,
                            'description' =>substr("Product ID: {$item->product_id}", 0, 255),
                            'images' =>[$item->image_path ? Application::$app->request->getHostInfo() . $item->image_path : null],
                        ],
                        'unit_amount' => (int)($item->price), //check for later convert to cents
                    ],
                    'quantity' => $item->quantity,
                ];
                
            }

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
                    'allowed_countries' => ['US', 'CA', 'MA'], // US, Canada, Morocco
                ],
            ]);

            return $session->url;

        } catch (ApiErrorException $e) {
            Application::$app->session->setFlash('error', 'Payment Error: ' . $e->getMessage());
            throw new \Exception('Failed to create checkout session: ' . $e->getMessage());
        }

    }

        public function handleWebhook(string $payload, string $sigHeader): bool
        {
            try {
                $event = \Stripe\Webhook::constructEvent(
                    $payload, $sigHeader, $this->webhookSecret
                );
                
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

        private function handlePaymentIntentSucceeded($paymentIntent): bool
        {
            $orderService = new OrderService();
            return $orderService->updateOrderStatus($paymentIntent->id, 'paid');
        }
        private function handlePaymentIntentFailed($paymentIntent): bool
    {
        $orderService = new OrderService();
        return $orderService->updateOrderStatus($paymentIntent->id, 'failed');
    }
}