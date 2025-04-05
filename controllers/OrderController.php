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
    
    
 