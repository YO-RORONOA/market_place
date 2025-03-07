<?php

namespace App\controllers;

use App\core\Controller;
use App\core\Request;
use App\models\RegisterModel;
use App\models\User;
use AuthService;

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
                return $this->response->redirect('/login');
            }

            
        }
        $this->setLayout('auth');

        return $this->render('auth/register', ['model' => $user]);


    }

}