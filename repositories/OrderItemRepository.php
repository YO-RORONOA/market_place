<?php

namespace App\repositories;

use App\repositories\Repository;

class OrderItemRepository extends Repository
{
    protected string $table = 'order_items';
    protected array $fillable = ['order_id', 'product_id', 'quantity', 'price'];
    
    /**
     * Find order items by order ID
     *
     * @param int $orderId
     * @return array
     */
    public function findByOrderId(int $orderId): array
    {
        $sql = "SELECT oi.*, p.name, p.image_path 
                FROM {$this->table} oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = :order_id";
        
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':order_id', $orderId);
        $statement->execute();
        
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get total sales by product
     *
     * @param int $limit
     * @return array
     */
    public function getTopSellingProducts(int $limit = 10): array
    {
        $sql = "SELECT p.id, p.name, p.image_path, 
                       SUM(oi.quantity) as total_quantity,
                       SUM(oi.quantity * oi.price) as total_sales
                FROM {$this->table} oi
                JOIN products p ON oi.product_id = p.id
                JOIN orders o ON oi.order_id = o.id
                WHERE o.status = 'paid' AND p.deleted_at IS NULL
                GROUP BY p.id, p.name, p.image_path
                ORDER BY total_quantity DESC
                LIMIT :limit";
        
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $statement->execute();
        
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}