<?php

namespace App\Core\Middlewares;

use App\core\Application;
use App\core\middlewares\BaseMiddleware;
use ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{

    public array $actions = [];

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if(empty($this->actions) || in_array(Application::$app->controller->action, $this->actions))
        {
            if(!Application::$app->session->get('user'))
            {
                throw new ForbiddenException();
            }
        }
    }


}