<?php
namespace App\core\middlewares;

use App\core\Application;


class GuestMiddleware extends BaseMiddleware
{
    public array $actions = [];
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        // If actions array is empty, apply to all actions
        // If not empty, only apply to the specified actions
        if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
            if (Application::$app->session->get('user')) {
                Application::$app->session->setFlash('info', 'You are already logged in.');
                Application::$app->response->redirect('/');
                exit;
            }
        }
    }
}