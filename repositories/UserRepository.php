<?php

namespace App\Repositories;

use App\models\User;


class UserRepository extends Repository
{
    protected string $table = 'users';
    protected array $fillable = ['firstname', 'lastname', 'email', 'password', 'role_id', 'status', 'verification_token', 'email_verified_at'];


    public function findByEmail(string $email): User|null
    {
        $result = $this->findAll(['email' => $email]);
        if(empty($result))
        {
            return null;
        }

        $user = new User();
        $user->loadData($result[0]);
        return $user;
    }


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


    public function removePasswordResetToken(string $token): bool
    {
        $sql = "DELETE FROM password_resets WHERE token = :token";
        $statement = $this->db->pdo->prepare($sql);
        $statement->bindValue(':token', $token);
        
        return $statement->execute();
    }
    


}