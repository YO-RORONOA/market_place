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
    
    