<?php

namespace App\repositories;

use App\repositories\Repository;

class OrderRepository extends Repository
{
    protected string $table = 'orders';
    protected array $fillable = [
        'user_id', 'total_amount', 'status', 'payment_intent_id', 
        'payment_method', 'shipping_address'
    ];
    
    /**
     * Find order by payment intent ID
     *
     * @param string $paymentIntentId
     * @return array|null
     */
    public function findByPaymentIntentId(string $paymentIntentId): ?array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE payment_intent_id = :payment_intent_id 
                AND deleted_at IS NULL";
        
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':payment_intent_id', $paymentIntentId);
        $statement->execute();
        
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Get orders with items included
     *
     * @param array $conditions
     * @return array
     */
    public function getOrdersWithItems(array $conditions = []): array
    {
        $orders = $this->findAll($conditions);
        
        if (empty($orders)) {
            return [];
        }
        
        $orderIds = array_column($orders, 'id');
        $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
        
        $sql = "SELECT oi.*, p.name, p.image_path 
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id IN ($placeholders)";
        
        $statement = $this->db->pdo->prepare($sql);
        
        foreach ($orderIds as $index => $id) {
            $statement->bindValue($index + 1, $id);
        }
        
        $statement->execute();
        $items = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        // Group items by order_id
        $groupedItems = [];
        foreach ($items as $item) {
            $groupedItems[$item['order_id']][] = $item;
        }
        
        // Add items to each order
        foreach ($orders as &$order) {
            $order['items'] = $groupedItems[$order['id']] ?? [];
        }
        
        return $orders;
    }
    
    /**
     * Count orders by status
     *
     * @param string $status
     * @return int
     */
    public function countByStatus(string $status): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} 
                WHERE status = :status AND deleted_at IS NULL";
        
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':status', $status);
        $statement->execute();
        
        return (int) $statement->fetchColumn();
    }
}