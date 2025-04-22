<?php

namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\core\Request;
use App\core\middlewares\AuthMiddleware;
use App\services\CartService;
use App\services\PaymentService;
use App\services\OrderService;
use App\repositories\UserRepository;
use App\helpers\StripeDebugHelper;

class CheckoutController extends Controller
{
    private CartService $cartService;
    private PaymentService $paymentService;
    private OrderService $orderService;
    private UserRepository $userRepository;
    
    public function __construct()
    {
        parent::__construct();
        $this->cartService = new CartService();
        $this->paymentService = new PaymentService();
        $this->orderService = new OrderService();
        $this->userRepository = new UserRepository();
        
        // $this->registerMiddleware(new AuthMiddleware()); commennt for now check later
    }
    
    
    public function index()
    {
        if (!Application::$app->session->get('user')) {
            Application::$app->session->set('redirect_after_login', '/checkout');
            Application::$app->session->setFlash('info', 'Please login or register to continue checkout');
            Application::$app->response->redirect('/login');
            return;
        }


        $cartItems = $this->cartService->getCartItems();
        $cartTotal = $this->cartService->getCartTotal();
        
        if (empty($cartItems)) {
            Application::$app->session->setFlash('error', 'Your cart is empty');
            Application::$app->response->redirect('/cart');
            return;
        }
        
        return $this->render('checkout/index', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'title' => 'Checkout'
        ]);
    }
    
    
    public function process(Request $request)
    {
        if (!$request->isPost()) {
            Application::$app->response->redirect('/checkout');
            return;
        }
        
        $cartItems = $this->cartService->getCartItems();
        
        if (empty($cartItems)) {
            Application::$app->session->setFlash('error', 'Your cart is empty');
            Application::$app->response->redirect('/cart');
            return;
        }
        
        $userId = Application::$app->session->get('user')['id'] ?? 0;
        $userData = $this->userRepository->findOne($userId);
        
        if (!$userData) {
            Application::$app->session->setFlash('error', 'User not found');
            Application::$app->response->redirect('/login');
            return;
        }
        
        $user = new \App\models\User();
        $user->loadData($userData);
        
        try {
            $successUrl = Application::$app->request->getHostInfo() . '/checkout/success?session_id={CHECKOUT_SESSION_ID}';
            $cancelUrl = Application::$app->request->getHostInfo() . '/checkout/cancel';
            
            $checkoutUrl = $this->paymentService->createCheckoutSession(
                $cartItems,
                $user,
                $successUrl,
                $cancelUrl
            );
            
            Application::$app->response->redirect($checkoutUrl);
        } catch (\Exception $e) {
            error_log("Checkout error: " . $e->getMessage());
            Application::$app->session->setFlash('error', $e->getMessage());
            Application::$app->response->redirect('/checkout');
        }
    }
    
   
    public function success(Request $request)
    {
        $sessionId = $request->getQuery('session_id') ?? '';
        
        if (empty($sessionId)) {
            Application::$app->session->setFlash('error', 'Invalid session ID');
            Application::$app->response->redirect('/cart');
            return;
        }
        
        $order = $this->verifyOrder($sessionId);

        $this->cartService->clearCart();

        
        return $this->render('checkout/success', [
            'sessionId' => $sessionId,
            'order' => $order,
            'title' => 'Order Confirmation'
        ]);
    }
    
    
    private function verifyOrder(string $sessionId): ?array
    {
        $orderRepo = new \App\repositories\OrderRepository();
        $order = $orderRepo->findByPaymentIntentId($sessionId);
        
        if ($order) {
            return $this->orderService->getOrderById($order['id']);
        }
        
        try {
            $userId = Application::$app->session->get('user')['id'] ?? 0;
            
            if (!$userId) {
                error_log("No user ID found in session during order verification");
                return null;
            }
            
         
            $totalAmount = $this->cartService->getCartTotal();
            
            $this->orderService->createOrderFromCheckout(
                $userId,
                $sessionId,
                $totalAmount,
                null 
            );
            
            $order = $orderRepo->findByPaymentIntentId($sessionId);
            
            if ($order) {
                error_log("Created order manually during verification for session: $sessionId");
                return $this->orderService->getOrderById($order['id']);
            }
            
            error_log("Failed to create order during verification for session: $sessionId");
            return null;
        } catch (\Exception $e) {
            error_log("Error verifying order: " . $e->getMessage());
            return null;
        }
    }
    
   
    public function cancel()
    {
        Application::$app->session->setFlash('info', 'Your order was cancelled');
        
        return $this->render('checkout/cancel', [
            'title' => 'Order Cancelled'
        ]);
    }
    
    
    public function webhook(Request $request)
    {
        $payload = file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        
        StripeDebugHelper::logWebhookRequest($_SERVER, $payload);
        
        if (empty($payload)) {
            error_log("Webhook Error: Empty payload");
            http_response_code(400);
            exit();
        }
        
        if (empty($sigHeader)) {
            error_log("Webhook Error: Missing Stripe signature header");
            http_response_code(400);
            exit();
        }
        
        $success = $this->paymentService->handleWebhook($payload, $sigHeader);
        
        if (!$success) {
            error_log("Webhook Error: Failed to process webhook");
            http_response_code(400);
            exit();
        }
        
        http_response_code(200);
        exit();
    }
    
   
    public function testWebhook(Request $request)
    {
        if (!Application::$app->session->get('user')) {
            Application::$app->session->setFlash('error', 'You must be logged in to test webhooks');
            Application::$app->response->redirect('/login');
            return;
        }
        
        if ($_ENV['APP_ENV'] !== 'development') {
            Application::$app->response->statusCode(404);
            return $this->render('_error', ['message' => 'Page not found']);
        }
        
        $userId = Application::$app->session->get('user')['id'];
        $cartTotal = $this->cartService->getCartTotal();
        
        $event = StripeDebugHelper::generateCheckoutSessionCompletedEvent($userId, $cartTotal);
        
        $success = StripeDebugHelper::processTestEvent($event);
        
        if ($success) {
            Application::$app->session->setFlash('success', 'Test webhook processed successfully');
        } else {
            Application::$app->session->setFlash('error', 'Failed to process test webhook');
        }
        
        Application::$app->response->redirect('/orders');
    }
}