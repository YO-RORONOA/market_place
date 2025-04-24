<?php

namespace App\Core\Middlewares;

use App\core\Application;
use App\core\middlewares\BaseMiddleware;
use App\core\exception\ForbiddenException;
use App\models\Role;
use App\repositories\VendorRepository;

class VendorMiddleware extends BaseMiddleware
{
    private VendorRepository $vendorRepository;
    
    public function __construct()
    {
        $this->vendorRepository = new VendorRepository();
    }
    
    public function execute()
    {
        $userData = Application::$app->session->get('user');
        
        if (!$userData) {
            throw new ForbiddenException('You must be logged in to access this page');
        }
        
        $roles = $userData['roles'] ?? [];
        $activeRole = $userData['active_role'] ?? null;
        
        if (!in_array(Role::VENDOR, $roles)) {
            throw new ForbiddenException('You need a vendor account to access this page');
        }
        
        if ($activeRole !== Role::VENDOR) {
            $userData['active_role'] = Role::VENDOR;
            Application::$app->session->set('user', $userData);
        }
        
        $vendor = $this->vendorRepository->findByUserId($userData['id']);
        
        if (!$vendor || $vendor->status !== 'active') {
            Application::$app->response->redirect('/vendor/waiting-approval');
            exit;
        }
    }
}