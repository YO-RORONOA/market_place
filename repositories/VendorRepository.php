<?php

namespace App\repositories;

use App\models\Vendor;

class VendorRepository extends Repository
{
    protected string $table = 'vendors';
    protected array $fillable = [
        'user_id', 'store_name', 'description', 'logo_path', 'status'
    ];
    
    /**
     * Find a vendor by user ID
     * 
     * @param int $userId User ID
     * @return Vendor|null Vendor model or null if not found
     */
    public function findByUserId(int $userId): ?Vendor
    {
        $result = $this->findAll(['user_id' => $userId]);
        
        if (empty($result)) {
            return null;
        }
        
        $vendor = new Vendor();
        $vendor->loadData($result[0]);
        return $vendor;
    }
    
    /**
     * Get store statistics
     * 
     * @param int $userId Vendor's user ID
     * @return array Array with store statistics
     */
    public function getStoreStats(int $userId): array
    {
        $vendor = $this->findByUserId($userId);
        
        if (!$vendor) {
            return [
                'productCount' => 0,
                'orderCount' => 0,
                'revenue' => 0
            ];
        }
        
        return $vendor->getStoreStats();
    }
    
    /**
     * Get top selling products for a vendor
     * 
     * @param int $userId Vendor's user ID
     * @param int $limit Number of products to return
     * @return array Top selling products
     */
    public function getTopProducts(int $userId, int $limit = 3): array
    {
        $vendor = $this->findByUserId($userId);
        
        if (!$vendor) {
            return [];
        }
        
        return $vendor->getTopProducts($limit);
    }
    
    /**
     * Get recent orders for a vendor
     * 
     * @param int $userId Vendor's user ID
     * @param int $limit Number of orders to return
     * @return array Recent orders
     */
    public function getRecentOrders(int $userId, int $limit = 3): array
    {
        $vendor = $this->findByUserId($userId);
        
        if (!$vendor) {
            return [];
        }
        
        return $vendor->getRecentOrders($limit);
    }

    public function findAll(array $conditions = [], bool $withTrashed = false, ?string $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        $tableName = $this->table;
        $sql = "SELECT * FROM $tableName";

        $whereCondition = [];
        $params = [];

        if (!$withTrashed) {
            $whereCondition[] = "deleted_at IS NULL";
        }

        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $whereCondition[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        if (!empty($whereCondition)) {
            $sql .= " WHERE " . implode(' AND ', $whereCondition);
        }

        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }

        if ($limit) {
            $sql .= " LIMIT :limit";
            $params[":limit"] = $limit;
        }

        if ($offset) {
            $sql .= " OFFSET :offset";
            $params[":offset"] = $offset;
        }
        
        // Log the query for debugging
        error_log("Vendor query: $sql with params: " . json_encode($params));

        try {
            $statement = $this->db->pdo->prepare($sql);

            foreach ($params as $key => $value) {
                $paramType = is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR;
                $statement->bindValue($key, $value, $paramType);
            }

            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            
            // Log the result count
            error_log("Found " . count($result) . " vendors matching the criteria");
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Database error in VendorRepository::findAll: " . $e->getMessage());
            return [];
        }
    }
}