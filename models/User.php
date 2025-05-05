<?php

namespace App\models;

use App\core\Dbmodal\Dbmodal;
use App\repositories\UserRoleRepository;
use App\core\Application;

/**
 * Class User
 *
 * This class represents a user in the market system.
 * It includes properties and methods for managing user data.
 * The User class interacts with the database to perform CRUD operations.
 * 
 * @package Market\Models
 */
 
class User extends Dbmodal
{
    public ?int $id = null;
    public string $firstname = ''; //attributes accessed before init
    public string $lastname = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirm = '';
    public array $roles = []; // Now storing multiple roles
    public string $status = UserStatus::PENDING;
    public ?string $verification_token = null;
    public ?string $email_verified_at = null;
    
    // Used temporarily for registration
    public ?int $primary_role_id = null;

    public function tableName(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return['firstname', 'lastname', 'email', 'password',
         'status', 'verification_token', 'email_verified_at'];
    }

    public function register()
    {
        echo 'creating new user';
    }

    public function rules()
    {
        return[
            'firstname' => [
                self::RULE_REQUIRED, 
                self::RULE_LETTERS_ONLY,
                [self::RULE_MIN, 'min' => 2],
                [self::RULE_MAX, 'max' => 50]
            ],
            'lastname' => [
                self::RULE_REQUIRED,
                self::RULE_LETTERS_ONLY,
                [self::RULE_MIN, 'min' => 2],
                [self::RULE_MAX, 'max' => 50]
            ],
            'email' => [
                self::RULE_REQUIRED, 
                self::RULE_EMAIL, 
                [self::RULE_UNIQUE, 'class' => self::class]
            ],
            'password' => [
                self::RULE_REQUIRED, 
                [self::RULE_MIN, 'min' => 8], 
                [self::RULE_MAX, 'max' => 30]
            ],
            'passwordConfirm' => [
                self::RULE_REQUIRED, 
                [self::RULE_MATCH, 'match'=> 'password']
            ],
            'status' => [self::RULE_REQUIRED]
        ];
    }

    public function save(): bool  
{
    $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    
    // Save the user first
    $result = parent::save();
    
    // Get the ID of the newly inserted user (only if result is true)
    if ($result && $this->primary_role_id) {
        // Get the last insert ID from the database
        $this->id = Application::$app->db->pdo->lastInsertId();
        
        // Now add the role with the correct ID
        $userRoleRepository = new UserRoleRepository();
        $userRoleRepository->addRoleToUser($this->id, $this->primary_role_id);
    }
    
    return $result;
}

    public function update()
    {
        return parent::update();
    }

    public function getDisplayName(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::ACTIVE;
    }

    public function isPending(): bool
    {
        return $this->status === UserStatus::PENDING;
    }

    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }
    
    
    public function loadRoles(): array
    {
        if (!$this->id) {
            return [];
        }
        
        $userRoleRepository = new UserRoleRepository();
        $this->roles = $userRoleRepository->getUserRoles($this->id);
        
        return $this->roles;
    }
    
    
    public function hasRole(int $roleId): bool
    {
        // If roles haven't been loaded yet, load them
        if (empty($this->roles) && $this->id) {
            $this->loadRoles();
        }
        
        return in_array($roleId, $this->roles);
    }
    
    
    public function addRole(int $roleId): bool
    {
        if (!$this->id) {
            return false;
        }
        
        $userRoleRepository = new UserRoleRepository();
        $result = $userRoleRepository->addRoleToUser($this->id, $roleId);
        
        if ($result) {
            $this->roles[] = $roleId;
        }
        
        return $result;
    }
    
    
    public function removeRole(int $roleId): bool
    {
        if (!$this->id) {
            return false;
        }
        
        $userRoleRepository = new UserRoleRepository();
        $result = $userRoleRepository->removeRoleFromUser($this->id, $roleId);
        
        if ($result) {
            $this->roles = array_filter($this->roles, function($role) use ($roleId) {
                return $role != $roleId;
            });
        }
        
        return $result;
    }
}