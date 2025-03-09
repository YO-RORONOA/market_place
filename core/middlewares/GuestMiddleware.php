<?php
namespace App\core\middlewares;

use App\core\Application;


class GuestMiddleware extends BaseMiddleware
{
    public function execute()
    {
        if (Application::$app->session->get('user')) {
            Application::$app->session->setFlash('info', 'You are already logged in.');
            Application::$app->response->redirect('/');
            exit;
        }
    }
}