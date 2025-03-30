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
}