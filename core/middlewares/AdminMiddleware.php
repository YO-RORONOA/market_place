<?php

namespace App\Core\Middlewares;
use App\core\middlewares\BaseMiddleware;

class AdminMiddleware extends BaseMiddleware
{
    public function execute()
    {

    $user = Application::$app->session->get('user');
    if(!$user || $user['role'] !== 'admin')
    {
        throw new ForbiddenException();

    }
    }

}