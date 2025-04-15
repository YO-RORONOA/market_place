<?php

namespace App\helpers;

use App\core\Application;

class StripeDebugHelper
{
    /**
     * Create logs directory if it doesn't exist
     */
    public static function ensureLogDir(): void
    {
        $logDir = Application::$ROOT_DIR . '/logs';
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }
    
    /**
     * Log webhook request details for debugging
     *
     * @param array $server $_SERVER array
     * @param string $payload Request payload
     * @return void
     */
    public static function logWebhookRequest(array $server, string $payload): void
    {
        self::ensureLogDir();
        
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $server['REQUEST_METHOD'] ?? 'UNKNOWN',
            'uri' => $server['REQUEST_URI'] ?? 'UNKNOWN',
            'content_type' => $server['CONTENT_TYPE'] ?? 'UNKNOWN',
            'stripe_signature' => $server['HTTP_STRIPE_SIGNATURE'] ?? 'MISSING',
            'remote_addr' => $server['REMOTE_ADDR'] ?? 'UNKNOWN',
            'payload_length' => strlen($payload),
            'payload_preview' => substr($payload, 0, 500) . (strlen($payload) > 500 ? '...' : '')
        ];
        
        $logEntry = "[WEBHOOK REQUEST]\n";
        $logEntry .= json_encode($logData, JSON_PRETTY_PRINT) . "\n\n";
        
        file_put_contents(
            Application::$ROOT_DIR . '/logs/stripe-webhook-requests.log',
            $logEntry,
            FILE_APPEND
        );
    }
    
    /**
     * Generate test webhook event
     *
     * @param string $type Event type (e.g., 'checkout.session.completed')
     * @param array $data Event data
     * @return array Event data
     */
    public static function generateTestEvent(string $type, array $data = []): array
    {
        $event = [
            'id' => 'evt_test_' . bin2hex(random_bytes(16)),
            'object' => 'event',
            'api_version' => '2020-08-27',
            'created' => time(),
            'data' => [
                'object' => $data
            ],
            'livemode' => false,
            'pending_webhooks' => 0,
            'request' => [
                'id' => null,
                'idempotency_key' => null
            ],
            'type' => $type
        ];
        
        return $event;
    }
    
    /**
     * Generate sample checkout.session.completed event
     *
     * @param int $userId User ID
     * @param float $amount Order amount
     * @param string $sessionId Session ID (optional)
     * @return array Event data
     */
    public static function generateCheckoutSessionCompletedEvent(int $userId, float $amount, string $sessionId = null): array
    {
        $sessionId = $sessionId ?? 'cs_test_' . bin2hex(random_bytes(16));
        $paymentIntentId = 'pi_' . bin2hex(random_bytes(16));
        
        $sessionData = [
            'id' => $sessionId,
            'object' => 'checkout.session',
            'after_expiration' => null,
            'allow_promotion_codes' => null,
            'amount_subtotal' => (int)($amount * 100),
            'amount_total' => (int)($amount * 100),
            'billing_address_collection' => null,
            'cancel_url' => Application::$app->request->getHostInfo() . '/checkout/cancel',
            'client_reference_id' => (string)$userId,
            'customer' => null,
            'customer_details' => [
                'email' => 'test@example.com',
                'phone' => null,
                'tax_exempt' => 'none',
                'tax_ids' => null,
                'address' => [
                    'city' => 'Test City',
                    'country' => 'US',
                    'line1' => '123 Test St',
                    'line2' => 'Apt 4',
                    'postal_code' => '12345',
                    'state' => 'CA'
                ],
                'name' => 'Test User'
            ],
            'customer_email' => 'test@example.com',
            'expires_at' => time() + 3600,
            'livemode' => false,
            'locale' => null,
            'metadata' => [
                'user_id' => (string)$userId,
                'total_amount' => (string)$amount
            ],
            'mode' => 'payment',
            'payment_intent' => $paymentIntentId,
            'payment_method_options' => [],
            'payment_method_types' => ['card'],
            'payment_status' => 'paid',
            'shipping' => [
                'address' => [
                    'city' => 'Shipping City',
                    'country' => 'US',
                    'line1' => '456 Shipping St',
                    'line2' => '',
                    'postal_code' => '54321',
                    'state' => 'NY'
                ],
                'name' => 'Test User'
            ],
            'shipping_address_collection' => [
                'allowed_countries' => ['US', 'CA', 'MA']
            ],
            'status' => 'complete',
            'success_url' => Application::$app->request->getHostInfo() . '/checkout/success?session_id={CHECKOUT_SESSION_ID}',
            'url' => null
        ];
        
        return self::generateTestEvent('checkout.session.completed', $sessionData);
    }
    
    /**
     * Log test webhook event
     *
     * @param array $event Event data
     * @return void
     */
    public static function logTestEvent(array $event): void
    {
        self::ensureLogDir();
        
        $logEntry = "[TEST EVENT - " . $event['type'] . "]\n";
        $logEntry .= json_encode($event, JSON_PRETTY_PRINT) . "\n\n";
        
        file_put_contents(
            Application::$ROOT_DIR . '/logs/stripe-test-events.log',
            $logEntry,
            FILE_APPEND
        );
    }
    
    /**
     * Simulate processing a webhook event
     *
     * @param array $event Event data
     * @return bool Success status
     */
    public static function processTestEvent(array $event): bool
    {
        // Convert event to JSON
        $payload = json_encode($event);
        
        // Create a mock signature (not valid for real validation)
        $timestamp = time();
        $signature = 'test_signature';
        $sigHeader = "t=$timestamp,v1=$signature";
        
        // Log the test event
        self::logTestEvent($event);
        
        // Process the event with the payment service
        $paymentService = new \App\services\PaymentService();
        
        try {
            // Monkey patch the validation method
            // Note: This is a hack for testing only
            $reflectionClass = new \ReflectionClass('\\Stripe\\Webhook');
            $reflectionMethod = $reflectionClass->getMethod('constructEvent');
            $reflectionMethod->setAccessible(true);
            
            // Call the payment service webhook handler directly
            return $paymentService->handleWebhook($payload, $sigHeader);
        } catch (\Exception $e) {
            error_log('Error processing test event: ' . $e->getMessage());
            return false;
        }
    }
}