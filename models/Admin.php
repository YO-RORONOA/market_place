<?php

namespace App\models;

use App\core\Application;

class Admin extends User
{
    // Constants for admin-specific status
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    
    // Additional admin-specific properties if needed
    public bool $is_super_admin = false;
    
    /**
     * Check if user has admin role
     * 
     * @return bool
     */
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
    
    /**
     * Override rules to add admin-specific validation
     */
    public function rules(): array
    {
        $rules = parent::rules();
        
        // Add admin-specific rules if needed
        
        return $rules;
    }
}