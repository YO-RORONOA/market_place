<?php

namespace App\repositories;

use App\core\Application;
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


    public function count(array $conditions = []): int
{
    $sql = "SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL";
    $params = [];
    
    if (!empty($conditions)) {
        foreach ($conditions as $key => $value) {
            $sql .= " AND $key = :$key";
            $params[":$key"] = $value;
        }
    }
    
    $statement = $this->db->pdo->prepare($sql);
    
    foreach ($params as $key => $value) {
        $statement->bindValue($key, $value);
    }
    
    $statement->execute();
    return (int)$statement->fetchColumn();
}


public function totalRevenue(): float
{
    try {
        $sql = "SELECT SUM(total_amount) FROM {$this->table} 
                WHERE status = 'completed' AND deleted_at IS NULL";
        
        $statement = $this->db->pdo->prepare($sql);
        $statement->execute();
        
        $result = $statement->fetchColumn();
        return (float)($result ?: 0);
    } catch (\PDOException $e) {
        error_log("Error calculating total revenue: " . $e->getMessage());
        return 0;
    }
}

public function getRevenueInDateRange(string $startDate, string $endDate): float
{
    try {
        $sql = "SELECT SUM(total_amount) FROM {$this->table} 
                WHERE status = 'completed' 
                AND created_at BETWEEN :start_date AND :end_date
                AND deleted_at IS NULL";
        
        $statement = Application::$app->db->pdo->prepare($sql);
        $statement->bindValue(':start_date', $startDate . ' 00:00:00');
        $statement->bindValue(':end_date', $endDate . ' 23:59:59');
        $statement->execute();
        
        $result = $statement->fetchColumn();
        return (float)($result ?: 0);
    } catch (\PDOException $e) {
        error_log("Error calculating revenue in date range: " . $e->getMessage());
        return 0;
    }
}


}


