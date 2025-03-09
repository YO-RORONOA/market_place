<?php

namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\core\Request;
use App\models\RegisterModel;
use App\models\User;
use App\Services\AuthService;


class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }
    public function login()
    {
        $this->setLayout('auth');
        return $this->render('login');
    }

    public function register(Request $request)
    {
        $user = new User();

        if($request->isPost())
        {
            $user->loadData($request->getbody());

            if($this->authService->register($request->getbody()))
            {
                return Application::$app->response->redirect('/login');
            }

            
        }
        $this->setLayout('auth');

        return $this->render('auth/register', ['model' => $user]);


    }
    
    public function logout()
    {
        $this->authService->logout();
        return $this->response->redirect('/');
    }

    
    public function verifyEmail(Request $request)
    {
        $token = $request->getBody()['token'] ?? '';
        
        if (empty($token) || !$this->authService->verifyEmail($token)) {
            return $this->response->redirect('/invalid-token');
        }
        
        return $this->response->redirect('/login');
    }
    
    public function forgotPassword(Request $request)
    {
        if ($request->isPost()) {
            $email = $request->getBody()['email'] ?? '';
            $this->authService->requestPasswordReset($email);
            
            return $this->response->redirect('/password-reset-sent');
        }
        
        $this->setLayout('auth');
        return $this->render('auth/forgot-password');
    }
    
    public function resetPassword(Request $request)
    {
        $token = $request->getQuery('token') ?? '';
        
        if (empty($token)) {
            return $this->response->redirect('/invalid-token');
        }
        
        if ($request->isPost()) {
            $password = $request->getBody()['password'] ?? '';
            
            if ($this->authService->resetPassword($token, $password)) {
                return $this->response->redirect('/login');
            }
        }
        
        $this->setLayout('auth');
        return $this->render('auth/reset-password', ['token' => $token]);
    }

}