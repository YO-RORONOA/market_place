<?php

namespace App\services;

use App\core\Application;
use App\repositories\OrderRepository;
use App\repositories\OrderItemRepository;

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
        // Format address
        $addressString = '';
        if ($shippingAddress) {
            $addressString = implode(', ', [
                $shippingAddress->line1 ?? '',
                $shippingAddress->line2 ?? '',
                $shippingAddress->city ?? '',
                $shippingAddress->state ?? '',
                $shippingAddress->postal_code ?? '',
                $shippingAddress->country ?? ''
            ]);
        }
        
        // Create order
        $orderData = [
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_intent_id' => $paymentIntentId,
            'payment_method' => 'stripe',
            'shipping_address' => $addressString
        ];
        
        $orderId = $this->orderRepository->create($orderData);
        
        if (!$orderId) {
            return false;
        }
        
        // Add order items
        $cartItems = $this->cartService->getCartItems();
        
        foreach ($cartItems as $item) {
            $orderItemData = [
                'order_id' => $orderId,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price
            ];
            
            $this->orderItemRepository->create($orderItemData);
        }
        
        // Clear the cart
        $this->cartService->clearCart();
        
        return true;
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
        
        return $this->orderRepository->update($order['id'], ['status' => $status]);
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
        
        // Get order items
        $orderItems = $this->orderItemRepository->findByOrderId($orderId);
        $order['items'] = $orderItems;
        
        return $order;
    }
    
    /**
     * Get orders by user ID
     *
     * @param int $userId User ID
     * @return array
     */
    public function getOrdersByUserId(int $userId): array
    {
        return $this->orderRepository->findAll(['user_id' => $userId]);
    }
}