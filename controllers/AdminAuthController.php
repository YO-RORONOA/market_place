<?php

namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\core\exception\ForbiddenException;
use App\core\middlewares\GuestMiddleware;
use App\core\Request;
use App\models\LoginForm;
use App\models\Role;
use App\models\User;
use App\services\AuthService;

class AdminAuthController extends Controller
{
    private AuthService $authService;
    
    public function __construct()
    {
        parent::__construct();
        $this->setLayout('admin');
        $this->authService = new AuthService();
        
        // Use guest middleware for login
        // $this->registerMiddleware(new GuestMiddleware(['login']));
    }
    
 
    public function login(Request $request)
    {
        $loginForm = new LoginForm();
        
        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            
            if ($loginForm->validate()) {
                if ($this->authService->login($loginForm->email, $loginForm->password, $loginForm->rememberMe ?? false, Role::ADMIN)) {
                    Application::$app->session->setFlash('success', 'Welcome to the admin dashboard!');
                    Application::$app->response->redirect('/admin/dashboard');
                    return;
                } else {
                    $loginForm->addError('password', 'Invalid admin credentials');
                }
            }
        }
        
        return $this->render('admin/auth/login', [
            'model' => $loginForm,
            'title' => 'Admin Login'
        ]);
    }

    public function logout()
    {
        $this->authService->logout();
        Application::$app->session->setFlash('success', 'You have been logged out successfully.');
        Application::$app->response->redirect('/admin/login');
    }
    
    
    public function dashboard()
    {
        return $this->render('admin/dashboard', [
            'title' => 'Admin Dashboard'
        ]);
    }
}