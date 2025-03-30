<?php

namespace App\repositories;

use App\core\Application;
use App\repositories\Repository;

class UserRoleRepository extends Repository
{
    protected string $table = 'user_roles';
    protected array $fillable = ['user_id', 'role_id'];
    
    /**
     * Get all roles for a user
     * 
     * @param int $userId The user ID
     * @return array Array of role IDs
     */
    public function getUserRoles(int $userId): array
    {
        $sql = "SELECT role_id FROM {$this->table} WHERE user_id = :user_id";
        
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId);
        $statement->execute();
        
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }
    
    /**
     * Check if a user has a specific role
     * 
     * @param int $userId The user ID
     * @param int $roleId The role ID
     * @return bool True if the user has the role, false otherwise
     */
    public function userHasRole(int $userId, int $roleId): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} 
                WHERE user_id = :user_id AND role_id = :role_id";
        
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId);
        $statement->bindValue(':role_id', $roleId);
        $statement->execute();
        
        return (int)$statement->fetchColumn() > 0;
    }
    
    /**
     * Add a role to a user
     * 
     * @param int $userId The user ID
     * @param int $roleId The role ID
     * @return bool True if successful, false otherwise
     */
    public function addRoleToUser(int $userId, int $roleId): bool
    {
        if ($this->userHasRole($userId, $roleId)) {
            return true; 
        }
        
        return $this->create([
            'user_id' => $userId,
            'role_id' => $roleId
        ]) > 0;
    }
    
    /**
     * Remove a role from a user
     * 
     * @param int $userId The user ID
     * @param int $roleId The role ID
     * @return bool True if successful, false otherwise
     */
    public function removeRoleFromUser(int $userId, int $roleId): bool
    {
        $sql = "DELETE FROM {$this->table} 
                WHERE user_id = :user_id AND role_id = :role_id";
        
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId);
        $statement->bindValue(':role_id', $roleId);
        
        return $statement->execute();
    }
    
    /**
     * Get users with a specific role
     * 
     * @param int $roleId The role ID
     * @return array Array of user IDs
     */
    public function getUsersByRole(int $roleId): array
    {
        $sql = "SELECT user_id FROM {$this->table} WHERE role_id = :role_id";
        
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':role_id', $roleId);
        $statement->execute();
        
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }
}