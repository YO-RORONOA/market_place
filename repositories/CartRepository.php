<?php

namespace App\repositories;

use App\core\Application;
use App\repositories\Repository;

class CartRepository extends Repository
{
    protected string $table = 'user_cart_items';
    protected array $fillable = ['user_id', 'product_id', 'quantity'];
    

    public function getCartItems(int $userId): array
    {
        $sql = "SELECT uci.product_id, uci.quantity, p.name, p.price, p.image_path 
                FROM {$this->table} uci
                JOIN products p ON uci.product_id = p.id
                WHERE uci.user_id = :user_id";
                
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        
        try {
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error loading cart from database: " . $e->getMessage());
            return [];
        }
    }

    public function clearCart(int $userId): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE user_id = :user_id";
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        
        try {
            return $statement->execute();
        } catch (\PDOException $e) {
            error_log("Error clearing cart from database: " . $e->getMessage());
            return false;
        }
    }
   
    public function saveCartItems(int $userId, array $cartItems): bool
    {
        if (empty($cartItems)) {
            return true;
        }
        
        $this->clearCart($userId);
        
        $sql = "INSERT INTO {$this->table} (user_id, product_id, quantity, created_at, updated_at) VALUES ";
        $values = [];
        $params = [];
        
        foreach ($cartItems as $index => $item) {
            $paramPrefix = "p" . $index;
            $values[] = "(:user_id, :{$paramPrefix}_product_id, :{$paramPrefix}_quantity, NOW(), NOW())";
            $params["{$paramPrefix}_product_id"] = $item['product_id'];
            $params["{$paramPrefix}_quantity"] = $item['quantity'];
        }
        
        $sql .= implode(", ", $values);
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        
        foreach ($params as $key => $value) {
            $statement->bindValue(":{$key}", $value);
        }
        
        try {
            return $statement->execute();
        } catch (\PDOException $e) {
            error_log("Error saving cart to database: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateCartItemQuantity(int $userId, int $productId, int $quantity): bool
    {
        $sql = "UPDATE {$this->table} SET quantity = :quantity, updated_at = NOW() 
                WHERE user_id = :user_id AND product_id = :product_id";
                
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindValue(':product_id', $productId, \PDO::PARAM_INT);
        $statement->bindValue(':quantity', $quantity, \PDO::PARAM_INT);
        
        try {
            return $statement->execute();
        } catch (\PDOException $e) {
            error_log("Error updating cart item quantity: " . $e->getMessage());
            return false;
        }
    }
    
    public function removeCartItem(int $userId, int $productId): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE user_id = :user_id AND product_id = :product_id";
        
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindValue(':product_id', $productId, \PDO::PARAM_INT);
        
        try {
            return $statement->execute();
        } catch (\PDOException $e) {
            error_log("Error removing cart item: " . $e->getMessage());
            return false;
        }
    }
    

    public function productExistsInCart(int $userId, int $productId): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} 
                WHERE user_id = :user_id AND product_id = :product_id";
                
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindValue(':product_id', $productId, \PDO::PARAM_INT);
        
        try {
            $statement->execute();
            return (int)$statement->fetchColumn() > 0;
        } catch (\PDOException $e) {
            error_log("Error checking if product exists in cart: " . $e->getMessage());
            return false;
        }
    }
}