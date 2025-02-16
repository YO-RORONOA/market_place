<?php

namespace App\controllers;

use App\core\Controller;
use App\core\Request;
use App\models\RegisterModel;


class AuthController extends Controller
{
    public function login()
    {
        $this->setLayout('auth');
        return $this->render('login');
    }

    public function register(Request $request)
    {
        $registerModel = new RegisterModel();

        if($request->isPost())
        {
            $registerModel->loadData($request->getbody());

            



            if($registerModel->validate() && $registerModel->register())
            {
                return 'sucess page';
            }
            echo '<pre>';
            var_dump($registerModel->errors);
            echo '</pre>';
            exit;

            // else return $this->render('register', [
            //     'model' => $registerModel
            // ]);

            echo 'handle post register';
        }
        $this->setLayout('auth');

    return $this->render('register', [
        'model' => $registerModel
    ]);


    }

}