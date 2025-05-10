<?php

namespace App\repositories;

use App\core\Application;
use App\models\User;
use App\repositories\Repository;
use App\repositories\UserRoleRepository;

class UserRepository extends Repository
{
    protected string $table = 'users';
    protected array $fillable = ['firstname', 'lastname', 'email', 'password', 'status', 'verification_token', 'email_verified_at'];

    /**
     * Find a user by email address
     * 
     * @param string $email Email address to search for
     * @return User|null User object if found, null otherwise
     */
    public function findByEmail(string $email): ?User
    {
        $result = $this->findAll(['email' => $email]);
        if(empty($result))
        {
            return null;
        }

        $user = new User();
        $user->loadData($result[0]);
        
        // Load user roles
        $userRoleRepository = new UserRoleRepository();
        $user->roles = $userRoleRepository->getUserRoles($user->id);
        
        return $user;
    }

    /**
     * Find a user by verification token
     * 
     * @param string $token Verification token
     * @return User|null User object if found, null otherwise
     */
    public function findByVerificationToken(string $token)
    {
        $result = $this->findAll(['verification_token' => $token]);
        
        if (empty($result)) {
            return null;
        }
        
        $user = new User();
        $user->loadData($result[0]);
        
        // Load user roles
        $userRoleRepository = new UserRoleRepository();
        $user->roles = $userRoleRepository->getUserRoles($user->id);
        
        return $user;
    }

    /**
     * Save a password reset token for a user
     * 
     * @param int $userId User ID
     * @param string $token Reset token
     * @return bool True if successful, false otherwise
     */
    public function savePasswordResetToken(int $userId, string $token): bool
    {
        $sql = "INSERT INTO password_resets(user_id, token, created_at)
        VALUES (:user_id, :token, :created_at)";

        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':user_id', $userId);
        $statement->bindValue(':token', $token);
        $statement->bindValue(':created_at', date('Y-m-d H:i:s'));

        return $statement->execute();
    }

    /**
     * Find a user ID by password reset token
     * 
     * @param string $token Reset token
     * @return int|null User ID if found, null otherwise
     */
    public function findUserIdByPasswordResetToken(string $token)
    {
        $expiration = date('Y-m-d H:i:s', strtotime('-1 hour'));

        $sql = "SELECT user_id FROM password_resets
        WHERE token = :token AND created_at > :expiration
        ORDER BY created_at DESC LIMIT 1";

        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':token', $token);
        $statement->bindValue(':expiration', $expiration);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result ? $result['user_id'] : null;
    }

    /**
     * Remove a password reset token
     * 
     * @param string $token Reset token
     * @return bool True if successful, false otherwise
     */
    public function removePasswordResetToken(string $token): bool
    {
        $sql = "DELETE FROM password_resets WHERE token = :token";
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':token', $token);
        
        return $statement->execute();
    }
    
    /**
     * Find a user by ID and load all their roles
     * 
     * @param int $id User ID
     * @param bool $withTrashed Include soft deleted users
     * @return User|null User object if found, null otherwise
     */
    public function findById(int $id, bool $withTrashed = false): ?User
    {
        $userData = $this->findOne($id, $withTrashed);
        
        if (!$userData) {
            return null;
        }
        
        $user = new User();
        $user->loadData($userData);
        
        // Load user roles
        $userRoleRepository = new UserRoleRepository();
        $user->roles = $userRoleRepository->getUserRoles($user->id);
        
        return $user;
    }

    public function countInDateRange(string $startDate, string $endDate): int
{
    try {
        $sql = "SELECT COUNT(*) FROM {$this->table} 
                WHERE created_at BETWEEN :start_date AND :end_date
                AND deleted_at IS NULL";
        
        $statement = Application::$app->db->pdo->prepare($sql);
        $statement->bindValue(':start_date', $startDate . ' 00:00:00');
        $statement->bindValue(':end_date', $endDate . ' 23:59:59');
        $statement->execute();
        
        return (int)$statement->fetchColumn();
    } catch (\PDOException $e) {
        error_log("Error counting users in date range: " . $e->getMessage());
        return 0;
    }
}
}