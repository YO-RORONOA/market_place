<?php

namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\core\Request;
use App\core\middlewares\AuthMiddleware;
use App\services\CartService;
use App\services\PaymentService;
use App\services\OrderService;
use App\Repositories\UserRepository;

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
        
        // Require authentication for all checkout actions
        $this->registerMiddleware(new AuthMiddleware());
    }
    
    /**
     * Display checkout page
     */
    public function index()
    {
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
    
    /**
     * Process checkout and redirect to Stripe
     */
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
        
        // Get current user
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
            // Success and cancel URLs
            $successUrl = Application::$app->request->getHostInfo() . '/checkout/success?session_id={CHECKOUT_SESSION_ID}';
            $cancelUrl = Application::$app->request->getHostInfo() . '/checkout/cancel';
            
            // Create Stripe checkout session
            $checkoutUrl = $this->paymentService->createCheckoutSession(
                $cartItems,
                $user,
                $successUrl,
                $cancelUrl
            );
            
            // Redirect to Stripe checkout
            Application::$app->response->redirect($checkoutUrl);
        } catch (\Exception $e) {
            Application::$app->session->setFlash('error', $e->getMessage());
            Application::$app->response->redirect('/checkout');
        }
    }
    
    /**
     * Handle successful checkout
     */
    public function success(Request $request)
    {
        // Get session ID from URL
        $sessionId = $request->getQuery('session_id') ?? '';
        
        if (empty($sessionId)) {
            Application::$app->session->setFlash('error', 'Invalid session ID');
            Application::$app->response->redirect('/cart');
            return;
        }
        
        return $this->render('checkout/success', [
            'sessionId' => $sessionId,
            'title' => 'Order Confirmation'
        ]);
    }
    
    /**
     * Handle cancelled checkout
     */
    public function cancel()
    {
        Application::$app->session->setFlash('info', 'Your order was cancelled');
        
        return $this->render('checkout/cancel', [
            'title' => 'Order Cancelled'
        ]);
    }
    
    /**
     * Handle Stripe webhooks
     */
    public function webhook(Request $request)
    {
        // Get the request payload and Stripe signature
        $payload = file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        
        if (empty($payload) || empty($sigHeader)) {
            http_response_code(400);
            exit();
        }
        
        // Handle the webhook
        $success = $this->paymentService->handleWebhook($payload, $sigHeader);
        
        if (!$success) {
            http_response_code(400);
            exit();
        }
        
        http_response_code(200);
        exit();
    }
}