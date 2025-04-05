<?php

namespace App\services;

use App\core\Application;
use App\repositories\OrderRepository;
use App\repositories\OrderItemRepository;
use App\models\CartItem;

class OrderService
{
    private CartService $cartService;
    private OrderRepository $orderRepository;
    private OrderItemRepository $orderItemRepository;
    
    public function __construct()
    {
        $this->cartService = new CartService();
        $this->orderRepository = new OrderRepository();
        $this->orderItemRepository = new OrderItemRepository();
    }
    
    /**
     * Create a new order from the current cart
     *
     * @param int $userId 
     * @param string $paymentIntentId Stripe payment intent ID
     * @param float $totalAmount Total order amount
     * @param object|null $shippingAddress Shipping address from Stripe
     * @return bool
     */
    public function createOrderFromCheckout(int $userId, string $paymentIntentId, float $totalAmount, ?object $shippingAddress): bool
    {
        $addressString = $this->formatShippingAddress($shippingAddress);
        
        $existingOrder = $this->orderRepository->findByPaymentIntentId($paymentIntentId);
        if ($existingOrder) {
            return $this->orderRepository->update($existingOrder['id'], [
                'status' => 'processing',
                'shipping_address' => $addressString
            ]);
        }
        
        $orderData = [
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'status' => 'processing',
            'payment_intent_id' => $paymentIntentId,
            'payment_method' => 'stripe',
            'shipping_address' => $addressString
        ];
        
        $orderId = $this->orderRepository->create($orderData);
        
        if (!$orderId) {
            return false;
        }
        
        $cartItems = $this->cartService->getCartItems();
        $success = $this->addItemsToOrder($orderId, $cartItems);
        
        if (!$success) {
            return false;
        }
        
        $this->cartService->clearCart();
        
        return true;
    }
    
    /**
     * Add items to an order
     *
     * @param int $orderId The order ID
     * @param array $items Array of CartItem objects
     * @return bool Success status
     */
    public function addItemsToOrder(int $orderId, array $items): bool
    {
        if (empty($items)) {
            return true;
        }
        
        $existingItems = $this->orderItemRepository->findByOrderId($orderId);
        if (!empty($existingItems)) {
            return true;
        }
        
        $success = true;
        
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ";
        $values = [];
        $params = [];
        
        foreach ($items as $index => $item) {
            $paramPrefix = "i" . $index;
            $values[] = "(:order_id, :{$paramPrefix}_product_id, :{$paramPrefix}_quantity, :{$paramPrefix}_price)";
            $params["{$paramPrefix}_product_id"] = $item->product_id;
            $params["{$paramPrefix}_quantity"] = $item->quantity;
            $params["{$paramPrefix}_price"] = $item->price;
        }
        
        $sql .= implode(", ", $values);
        
        try {
            $statement = Application::$app->db->pdo->prepare($sql);
            $statement->bindValue(':order_id', $orderId, \PDO::PARAM_INT);
            
            foreach ($params as $key => $value) {
                $statement->bindValue(":{$key}", $value);
            }
            
            $success = $statement->execute();
        } catch (\Exception $e) {
            $success = false;
        }
        
        return $success;
    }
    
    /**
     * Update an order's status
     *
     * @param string $paymentIntentId Stripe payment intent ID
     * @param string $status New status
     * @return bool
     */
    public function updateOrderStatus(string $paymentIntentId, string $status): bool
    {
        $order = $this->orderRepository->findByPaymentIntentId($paymentIntentId);
        
        if (!$order) {
            return false;
        }
        
        $result = $this->orderRepository->update($order['id'], ['status' => $status]);
        
        return $result;
    }
    
    /**
     * Format shipping address for storage
     *
     * @param object|null $address Address object from Stripe
     * @return string Formatted address
     */
    private function formatShippingAddress(?object $address): string
    {
        if (!$address) {
            return "No shipping address provided";
        }
        
        $addressParts = [];
        
        if (!empty($address->line1)) $addressParts[] = $address->line1;
        if (!empty($address->line2)) $addressParts[] = $address->line2;
        if (!empty($address->city)) $addressParts[] = $address->city;
        if (!empty($address->state)) $addressParts[] = $address->state;
        if (!empty($address->postal_code)) $addressParts[] = $address->postal_code;
        if (!empty($address->country)) $addressParts[] = $address->country;
        
        return !empty($addressParts) ? implode(', ', $addressParts) : "Address details not available";
    }
    
    /**
     * Get order by ID
     *
     * @param int $orderId Order ID
     * @return array|null
     */
    public function getOrderById(int $orderId): ?array
    {
        $order = $this->orderRepository->findOne($orderId);
        
        if (!$order) {
            return null;
        }
        
        $orderItems = $this->orderItemRepository->findByOrderId($orderId);
        $order['items'] = $orderItems;
        
        return $order;
    }
    
    public function getOrdersByUserId(int $userId): array
    {
        $orders = $this->orderRepository->findAll(['user_id' => $userId], false, 'created_at DESC');
        
        foreach ($orders as &$order) {
            $order['items'] = $this->orderItemRepository->findByOrderId($order['id']);
        }
        
        return $orders;
    }
   
    public function getOrdersByVendorId(int $vendorId): array
    {
        $sql = "SELECT DISTINCT o.id
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN products p ON oi.product_id = p.id
                WHERE p.vendor_id = :vendor_id AND o.deleted_at IS NULL
                ORDER BY o.created_at DESC";
                
        $statement = Application::$app->db->pdo->prepare($sql);
        $statement->bindValue(':vendor_id', $vendorId, \PDO::PARAM_INT);
        $statement->execute();
        
        $orderIds = $statement->fetchAll(\PDO::FETCH_COLUMN);
        
        if (empty($orderIds)) {
            return [];
        }
        
        $orders = [];
        
        foreach ($orderIds as $orderId) {
            $order = $this->getOrderById($orderId);
            
            if ($order) {
                $order['items'] = array_filter($order['items'], function($item) use ($vendorId) {
                    $productRepo = new \App\repositories\ProductRepository();
                    $product = $productRepo->findOne($item['product_id']);
                    return $product && $product['vendor_id'] == $vendorId;
                });
                
                $vendorTotal = 0;
                foreach ($order['items'] as $item) {
                    $vendorTotal += $item['price'] * $item['quantity'];
                }
                
                $order['vendor_total'] = $vendorTotal;
                $orders[] = $order;
            }
        }
        
        return $orders;
    }
    
    public function getOrderStatistics(): array
    {
        $totalOrders = $this->orderRepository->count();
        
        $pendingOrders = $this->orderRepository->countByStatus('pending');
        $processingOrders = $this->orderRepository->countByStatus('processing');
        $paidOrders = $this->orderRepository->countByStatus('paid');
        $failedOrders = $this->orderRepository->countByStatus('failed');
        
        $sql = "SELECT SUM(total_amount) FROM orders WHERE status = 'paid' AND deleted_at IS NULL";
        $totalRevenue = Application::$app->db->pdo->query($sql)->fetchColumn() ?: 0;
        
        $recentOrders = $this->orderRepository->findAll(
            ['status' => 'paid'], 
            false, 
            'created_at DESC', 
            5
        );
        
        return [
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'processingOrders' => $processingOrders,
            'paidOrders' => $paidOrders,
            'failedOrders' => $failedOrders,
            'totalRevenue' => $totalRevenue,
            'recentOrders' => $recentOrders
        ];
    }
}
