<?php

namespace App\models;

use App\core\Dbmodal\Dbmodal;

class Vendor extends Dbmodal
{
    public ?int $id = null;
    public int $user_id = 0; // Initialize with a default value
    public string $store_name = '';
    public string $description = '';
    public ?string $logo_path = null;
    public string $status = 'active';
    public string $created_at = '';
    public string $updated_at = '';

    public function tableName(): string
    {
        return 'vendors';
    }

    public function attributes(): array
    {
        return [
            'user_id',
            'store_name',
            'description',
            'logo_path',
            'status'
        ];
    }

    public function rules(): array
    {
        return [
            'store_name' => [self::RULE_REQUIRED],
            'description' => [self::RULE_REQUIRED],
            'status' => [self::RULE_REQUIRED]
            // user_id will be set programmatically, so no validation is needed here
        ];
    }

    /**
     * Gets the store statistics
     * 
     * @return array Store statistics
     */
    public function getStoreStats(): array
    {
        $productRepository = new \App\repositories\ProductRepository();
        $orderRepository = new \App\repositories\OrderRepository();
        
        $productCount = $productRepository->countProducts(['vendor_id' => $this->user_id]);
        
        // Get total revenue for this vendor
        $sql = "SELECT SUM(oi.price * oi.quantity) as total_revenue
                FROM order_items oi
                JOIN orders o ON oi.order_id = o.id
                JOIN products p ON oi.product_id = p.id
                WHERE p.vendor_id = :vendor_id AND o.status = 'paid'";
                
        $statement = $this->prepare($sql);
        $statement->bindValue(':vendor_id', $this->user_id);
        $statement->execute();
        $revenue = $statement->fetch(\PDO::FETCH_ASSOC)['total_revenue'] ?? 0;
        
        // Get order count for this vendor
        $sql = "SELECT COUNT(DISTINCT o.id) as order_count
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN products p ON oi.product_id = p.id
                WHERE p.vendor_id = :vendor_id";
                
        $statement = $this->prepare($sql);
        $statement->bindValue(':vendor_id', $this->user_id);
        $statement->execute();
        $orderCount = $statement->fetch(\PDO::FETCH_ASSOC)['order_count'] ?? 0;
        
        return [
            'productCount' => $productCount,
            'orderCount' => $orderCount,
            'revenue' => $revenue
        ];
    }
    
    /**
     * Gets the top selling products for this vendor
     * 
     * @param int $limit Number of products to return
     * @return array Top selling products
     */
    public function getTopProducts(int $limit = 3): array
    {
        $sql = "SELECT p.id, p.name, p.price, p.image_path, 
                       SUM(oi.quantity) as total_sold,
                       (SUM(oi.quantity) * 100.0 / 
                        (SELECT SUM(oi2.quantity) 
                         FROM order_items oi2 
                         JOIN products p2 ON oi2.product_id = p2.id
                         WHERE p2.vendor_id = :vendor_id)) as percentage
                FROM products p
                JOIN order_items oi ON p.id = oi.product_id
                JOIN orders o ON oi.order_id = o.id
                WHERE p.vendor_id = :vendor_id AND o.status = 'paid'
                GROUP BY p.id, p.name, p.price, p.image_path
                ORDER BY total_sold DESC
                LIMIT :limit";
                
        $statement = $this->prepare($sql);
        $statement->bindValue(':vendor_id', $this->user_id);
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $statement->execute();
        
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Gets the recent orders for this vendor
     * 
     * @param int $limit Number of orders to return
     * @return array Recent orders
     */
    public function getRecentOrders(int $limit = 3): array
    {
        $sql = "SELECT DISTINCT o.id, o.created_at, o.status, o.total_amount
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN products p ON oi.product_id = p.id
                WHERE p.vendor_id = :vendor_id
                ORDER BY o.created_at DESC
                LIMIT :limit";
                
        $statement = $this->prepare($sql);
        $statement->bindValue(':vendor_id', $this->user_id);
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $statement->execute();
        
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}