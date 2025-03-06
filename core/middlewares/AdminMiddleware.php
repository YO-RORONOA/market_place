<?php

namespace App\Core\Middlewares;

use App\core\Application;
use App\core\middlewares\BaseMiddleware;
use ForbiddenException;

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