<?php
/**
 * User: TheCodeholic
 * Date: 7/8/2020
 * Time: 8:43 AM
 */

namespace App\core;


/**
 * Class Controller
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\core
 */
class Controller
{
    public string $layout = 'main';
    public string $action = '';
    protected array $middlewares = [];
    public function setLayout($layout): void
    {
        $this->layout = $layout;
    }
    public function render($view, $params = []): string
    {
        return Application::$app->router->renderView($view, $params);
    }


    public function registerMiddleware($middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}