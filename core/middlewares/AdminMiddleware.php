<?php

namespace App\core\middlewares;

use App\core\Application;
use App\core\exception\ForbiddenException;
use App\models\Role;

class AdminMiddleware extends BaseMiddleware
{
    /**
     * Execute the middleware
     * 
     * @throws ForbiddenException if user is not logged in or not an admin
     */
    public function execute()
    {
        $userData = Application::$app->session->get('user');
        
        if (!$userData) {
            throw new ForbiddenException('You must be logged in to access this page');
        }
        
        $roles = $userData['roles'] ?? [];
        $activeRole = $userData['active_role'] ?? null;
        
        if (!in_array(Role::ADMIN, $roles)) {
            throw new ForbiddenException('You need admin privileges to access this page');
        }
        
        if ($activeRole !== Role::ADMIN) {
            $userData['active_role'] = Role::ADMIN;
            Application::$app->session->set('user', $userData);
        }
    }
}