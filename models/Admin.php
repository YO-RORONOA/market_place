<?php

namespace App\models;

use App\core\Application;

class Admin extends User
{
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    
    public bool $is_super_admin = false;
    
    
    public static function isAdmin(): bool
    {
        $userData = Application::$app->session->get('user') ?? null;
        
        if (!$userData) {
            return false;
        }
        
        $roles = $userData['roles'] ?? [];
        $activeRole = $userData['active_role'] ?? null;
        
        return in_array(Role::ADMIN, $roles) && $activeRole === Role::ADMIN;
    }
    
  
    public function rules(): array
    {
        $rules = parent::rules();
        
        
        return $rules;
    }
}