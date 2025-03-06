<?php

namespace App\Core\Middlewares;

use App\core\Application;
use App\core\middlewares\BaseMiddleware;
use ForbiddenException;

class VendorMiddleware extends BaseMiddleware
{
    public function execute()
    {
        $user = Application::$app->session->get('user');
        if(!$user || $user['role'] !== 'vendor')
        {
            throw new ForbiddenException();
        }
    }
}