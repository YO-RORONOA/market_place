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

}