<?php

namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\core\Request;
use App\core\middlewares\AuthMiddleware;
use App\services\OrderService;
use App\repositories\OrderRepository;
use App\repositories\OrderItemRepository;
use App\services\PaymentService;

class OrderController extends Controller
{
    private OrderService $orderService;
    private OrderRepository $orderRepository;
    private OrderItemRepository $orderItemRepository;
    private PaymentService $paymentService;
    
    public function __construct()
    {
        parent::__construct();
        $this->orderService = new OrderService();
        $this->orderRepository = new OrderRepository();
        $this->orderItemRepository = new OrderItemRepository();
        $this->paymentService = new PaymentService();
        
        $this->registerMiddleware(new AuthMiddleware());
    }
    
    /**
     * Display the user's orders with status filtering
     */
    public function index(Request $request)
    {
        $userId = Application::$app->session->get('user')['id'] ?? 0;
        
        if (!$userId) {
            Application::$app->session->setFlash('error', 'You must be logged in to view orders');
            Application::$app->response->redirect('/login');
            return;
        }
        
        $statusFilter = $request->getQuery('status') ?? '';
        
        $conditions = ['user_id' => $userId];
        if (!empty($statusFilter)) {
            $conditions['status'] = $statusFilter;
        }
        
        $orders = $this->orderRepository->findAll($conditions, false, 'created_at DESC');
        
        foreach ($orders as &$order) {
            $order['items'] = $this->orderItemRepository->findByOrderId($order['id']);
        }
        
        $totalOrders = count($orders);
        
        $availableStatuses = $this->getAvailableOrderStatuses();
        
        return $this->render('orders/index', [
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'availableStatuses' => $availableStatuses,
            'currentStatus' => $statusFilter,
            'title' => 'My Orders'
        ]);
    }
    
    /**
     * AJAX endpoint for filtering orders
     */
    public function ajax(Request $request)
    {
        $userId = Application::$app->session->get('user')['id'] ?? 0;
        
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        
        $statusFilter = $request->getQuery('status') ?? '';
        
        $conditions = ['user_id' => $userId];
        if (!empty($statusFilter)) {
            $conditions['status'] = $statusFilter;
        }
        
        // Get all orders for the user with filters applied
        $orders = $this->orderRepository->findAll($conditions, false, 'created_at DESC');
        
        foreach ($orders as &$order) {
            $order['items'] = $this->orderItemRepository->findByOrderId($order['id']);
            
            // Add a flag to indicate if the order is cancellable
            $order['can_cancel'] = $this->canCancelOrder($order['status']);
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'orders' => $orders,
            'totalCount' => count($orders),
            'status' => $statusFilter
        ]);
        exit;
    }
    
    /**
     * Display a specific order
     */
    public function view(Request $request)
    {
        $userId = Application::$app->session->get('user')['id'] ?? 0;
        
        if (!$userId) {
            Application::$app->session->setFlash('error', 'You must be logged in to view orders');
            Application::$app->response->redirect('/login');
            return;
        }
        
        $orderId = (int)$request->getQuery('id');
        
        if (!$orderId) {
            Application::$app->session->setFlash('error', 'Invalid order ID');
            Application::$app->response->redirect('/orders');
            return;
        }
        
        $order = $this->orderRepository->findOne($orderId);
        
        // Verify the order belongs to the current user
        if (!$order || $order['user_id'] != $userId) {
            Application::$app->session->setFlash('error', 'Order not found or you do not have permission to view it');
            Application::$app->response->redirect('/orders');
            return;
        }
        
        $items = $this->orderItemRepository->findByOrderId($orderId);
        
        // Check if order can be cancelled
        $canCancel = $this->canCancelOrder($order['status']);
        
        $orderProgress = $this->getOrderProgress($order['status']);
        
        return $this->render('orders/view', [
            'order' => $order,
            'items' => $items,
            'canCancel' => $canCancel,
            'orderProgress' => $orderProgress,
            'title' => 'Order #' . $orderId
        ]);
    }
    
    /**
     * Cancel an order
     */
    public function cancel(Request $request)
{
    error_log("Cancel method called with request: " . print_r($request->getQuery(), true));

    $userId = Application::$app->session->get('user')['id'] ?? 0;
    
    if (!$userId) {
        if ($request->isXhr()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        Application::$app->session->setFlash('error', 'You must be logged in to cancel an order');
        Application::$app->response->redirect('/login');
        return;
    }
    
    $orderId = (int)$request->getQuery('id');
    
    if (!$orderId) {
        if ($request->isXhr()) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
            exit;
        }
        
        Application::$app->session->setFlash('error', 'Invalid order ID');
        Application::$app->response->redirect('/orders');
        return;
    }
    
    $order = $this->orderRepository->findOne($orderId);
    error_log("Order details fetched: " . json_encode($order));
    
    if (!$order || $order['user_id'] != $userId) {
        if ($request->isXhr()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Order not found or you do not have permission to cancel it']);
            exit;
        }
        
        Application::$app->session->setFlash('error', 'Order not found or you do not have permission to cancel it');
        Application::$app->response->redirect('/orders');
        return;
    }
    
    if (!$this->canCancelOrder($order['status'])) {
        if ($request->isXhr()) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'This order cannot be cancelled']);
            exit;
        }
        
        Application::$app->session->setFlash('error', 'This order cannot be cancelled');
        Application::$app->response->redirect('/orders/view?id=' . $orderId);
        return;
    }
    
    error_log("Testing direct refund API call");
    $testRefundResult = $this->paymentService->testRefund();
    error_log("Test refund result: " . ($testRefundResult ? 'Success' : 'Failed'));
    
    $success = $this->processCancellation($order);
    
    if ($request->isXhr()) {
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Order cancelled successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to cancel order']);
        }
        exit;
    }
    
    if ($success) {
        Application::$app->session->setFlash('success', 'Order cancelled successfully. If payment was processed, a refund will be issued.');
    } else {
        Application::$app->session->setFlash('error', 'Failed to cancel order');
    }
    
    Application::$app->response->redirect('/orders/view?id=' . $orderId);
}
    
    /**
     * Check if an order can be cancelled based on its status
     * 
     * @param string $status Order status
     * @return bool True if the order can be cancelled
     */
    private function canCancelOrder(string $status): bool
    {
        // Orders can be cancelled if they are in pending or processing status
        
        return in_array($status, ['pending', 'processing']);
        
    }
    
    /**
     * Process order cancellation
     * 
     * @param array $order Order data
     * @return bool True if cancellation was successful
     */
    private function processCancellation(array $order): bool
    {
        try {
            $success = $this->orderRepository->update($order['id'], [
                'status' => 'cancelled'
            ]);
            
            if (!$success) {
                return false;
            }
            
            if (in_array($order['status'], ['paid', 'processing']) && !empty($order['payment_intent_id'])) {
                $this->paymentService->processRefund($order['payment_intent_id'], $order['total_amount']);
            }
            
            return true;
        } catch (\Exception $e) {
            error_log('Error cancelling order: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all available order statuses
     * 
     * @return array List of available order statuses
     */
    private function getAvailableOrderStatuses(): array
    {
     
        return [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'paid' => 'Paid',
            'shipped' => 'Shipped',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];
    }
    
    /**
     * Get order progress information for the timeline
     * 
     * @param string $status Current order status
     * @return array Order progress data
     */
    private function getOrderProgress(string $status): array
    {
        $steps = [
            [
                'name' => 'Order Placed',
                'description' => 'Your order has been placed and is pending confirmation.',
                'completed' => true, // All orders that exist have completed this step
                'current' => $status === 'pending',
            ],
            [
                'name' => 'Payment Received',
                'description' => 'Payment has been processed successfully.',
                'completed' => in_array($status, ['processing', 'paid', 'shipped', 'completed']),
                'current' => $status === 'processing',
            ],
            [
                'name' => 'Processing',
                'description' => 'Your order is being prepared for shipping.',
                'completed' => in_array($status, ['paid', 'shipped', 'completed']),
                'current' => $status === 'paid',
            ],
            [
                'name' => 'Shipped',
                'description' => 'Your order has been shipped and is on its way to you.',
                'completed' => in_array($status, ['shipped', 'completed']),
                'current' => $status === 'shipped',
            ],
            [
                'name' => 'Delivered',
                'description' => 'Your order has been delivered.',
                'completed' => $status === 'completed',
                'current' => $status === 'completed',
            ]
        ];
        
        if ($status === 'cancelled') {
            $steps = [
                [
                    'name' => 'Order Placed',
                    'description' => 'Your order was placed.',
                    'completed' => true,
                    'current' => false,
                ],
                [
                    'name' => 'Cancelled',
                    'description' => 'This order has been cancelled.',
                    'completed' => true,
                    'current' => true,
                    'cancelled' => true,
                ]
            ];
        }
        
        return $steps;
    }
}