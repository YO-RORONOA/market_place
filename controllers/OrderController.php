<?php

namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\core\Request;
use App\core\middlewares\AuthMiddleware;
use App\services\OrderService;
use App\repositories\OrderRepository;
use App\repositories\OrderItemRepository;

class OrderController extends Controller
{
    private OrderService $orderService;
    private OrderRepository $orderRepository;
    private OrderItemRepository $orderItemRepository;
    
    public function __construct()
    {
        parent::__construct();
        $this->orderService = new OrderService();
        $this->orderRepository = new OrderRepository();
        $this->orderItemRepository = new OrderItemRepository();
        
        $this->registerMiddleware(new AuthMiddleware());
    }
    
    
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
        
        $orders = $this->orderRepository->findAll($conditions, false, 'created_at DESC');
        
        foreach ($orders as &$order) {
            $order['items'] = $this->orderItemRepository->findByOrderId($order['id']);
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'orders' => $orders,
            'totalCount' => count($orders),
            'status' => $statusFilter
        ]);
        exit;
    }
    
 
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
        
        if (!$order || $order['user_id'] != $userId) {
            Application::$app->session->setFlash('error', 'Order not found or you do not have permission to view it');
            Application::$app->response->redirect('/orders');
            return;
        }
        
        $items = $this->orderItemRepository->findByOrderId($orderId);
        
        return $this->render('orders/view', [
            'order' => $order,
            'items' => $items,
            'title' => 'Order #' . $orderId
        ]);
    }
    
  
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
}