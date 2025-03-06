<?php

namespace App\Core\Middlewares;
use App\core\middlewares\BaseMiddleware;



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