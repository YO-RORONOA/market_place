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
        $userId = $this->getCurrentUserId();
        $cart = Application::$app->session->get(self::CART_SESSION_KEY);
        
        if ($userId && (empty($cart) || !is_array($cart))) {
            $cart = $this->loadCartFromDatabase($userId);
            Application::$app->session->set(self::CART_SESSION_KEY, $cart);
        }
        
        return is_array($cart) ? $cart : [];
    }
    
    public function addItem(int $productId, int $quantity = 1): bool
    {
        $product = $this->productRepository->findOne($productId);
        
        if (!$product) {
            return false;
        }
        
        $cart = $this->getCart();
        
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
        
        $this->persistCartToDatabase($cart);
        
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
        
        $this->persistCartToDatabase($cart);
        
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
        
        $this->persistCartToDatabase($cart);
        
        return true;
    }
    
    public function clearCart(): void
    {
        Application::$app->session->set(self::CART_SESSION_KEY, []);
        
        $userId = $this->getCurrentUserId();
        if ($userId) {
            $this->clearCartFromDatabase($userId);
        }
    }
    
    public function clearSessionCart(): void
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
    
    private function getCurrentUserId(): ?int
    {
        $user = Application::$app->session->get('user');
        return $user ? ($user['id'] ?? null) : null;
    }
    
    private function persistCartToDatabase(array $cart): bool
    {
        $userId = $this->getCurrentUserId();
        if (!$userId) {
            return false;
        }
        
        $this->clearCartFromDatabase($userId);
        
        if (empty($cart)) {
            return true;
        }
        
        $sql = "INSERT INTO user_cart_items (user_id, product_id, quantity, created_at, updated_at) VALUES ";
        $values = [];
        $params = [];
        
        foreach ($cart as $index => $item) {
            $paramPrefix = "p" . $index;
            $values[] = "(:user_id, :{$paramPrefix}_product_id, :{$paramPrefix}_quantity, NOW(), NOW())";
            $params["{$paramPrefix}_product_id"] = $item['product_id'];
            $params["{$paramPrefix}_quantity"] = $item['quantity'];
        }
        
        if (empty($values)) {
            return true;
        }
        
        $sql .= implode(", ", $values);
        $statement = Application::$app->db->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        
        foreach ($params as $key => $value) {
            $statement->bindValue(":{$key}", $value);
        }
        
        try {
            return $statement->execute();
        } catch (\Exception $e) {
            error_log("Error saving cart to database: " . $e->getMessage());
            return false;
        }
    }
    
    private function loadCartFromDatabase(int $userId): array
    {
        $sql = "SELECT uci.product_id, uci.quantity, p.name, p.price, p.image_path 
                FROM user_cart_items uci
                JOIN products p ON uci.product_id = p.id
                WHERE uci.user_id = :user_id";
                
        $statement = Application::$app->db->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        
        try {
            $statement->execute();
            $cartItems = $statement->fetchAll(\PDO::FETCH_ASSOC);
            
            $cart = [];
            foreach ($cartItems as $item) {
                $cart[$item['product_id']] = [
                    'product_id' => $item['product_id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'image_path' => $item['image_path']
                ];
            }
            
            return $cart;
        } catch (\Exception $e) {
            error_log("Error loading cart from database: " . $e->getMessage());
            return [];
        }
    }
    
    private function clearCartFromDatabase(int $userId): bool
    {
        $sql = "DELETE FROM user_cart_items WHERE user_id = :user_id";
        $statement = Application::$app->db->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        
        try {
            return $statement->execute();
        } catch (\Exception $e) {
            error_log("Error clearing cart from database: " . $e->getMessage());
            return false;
        }
    }
    
    public function initializeUserCart(int $userId): void
    {
        Application::$app->session->set(self::CART_SESSION_KEY, []);
        
        $cart = $this->loadCartFromDatabase($userId);
        
        Application::$app->session->set(self::CART_SESSION_KEY, $cart);
    }
}