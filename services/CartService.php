<?php

namespace App\services;

use App\core\Application;
use App\models\CartItem;
use App\repositories\ProductRepository;

class CartService
{
    private $productRepository;
    private const CART_SESSION_KEY = 'cart_items';
    
    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }
    
    public function getCart(): array
    {
        return Application::$app->session->get(self::CART_SESSION_KEY) ?? [];
    }
    
    public function addItem(int $productId, int $quantity = 1): bool
    {
        $product = $this->productRepository->findOne($productId);
        
        if (!$product) {
            return false;
        }
        
        $cart = $this->getCart();
        
        // Check if product already exists in cart
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image_path' => $product['image_path']
            ];
        }
        
        Application::$app->session->set(self::CART_SESSION_KEY, $cart);
        return true;
    }
    
    public function updateItem(int $productId, int $quantity): bool
    {
        $cart = $this->getCart();
        
        if (!isset($cart[$productId])) {
            return false;
        }
        
        if ($quantity <= 0) {
            return $this->removeItem($productId);
        }
        
        $cart[$productId]['quantity'] = $quantity;
        Application::$app->session->set(self::CART_SESSION_KEY, $cart);
        
        return true;
    }
    
    public function removeItem(int $productId): bool
    {
        $cart = $this->getCart();
        
        if (!isset($cart[$productId])) {
            return false;
        }
        
        unset($cart[$productId]);
        Application::$app->session->set(self::CART_SESSION_KEY, $cart);
        
        return true;
    }
    
    public function clearCart(): void
    {
        Application::$app->session->set(self::CART_SESSION_KEY, []);
    }
    
    public function getItemCount(): int
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'quantity'));
    }
    
    public function getCartTotal(): float
    {
        $cart = $this->getCart();
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }
    
    public function getCartItems(): array
    {
        $cart = $this->getCart();
        $items = [];
        
        foreach ($cart as $item) {
            $items[] = new CartItem(
                $item['product_id'],
                $item['name'],
                $item['price'],
                $item['quantity'],
                $item['image_path']
            );
        }
        
        return $items;
    }
}