<?php

namespace App\core;


class Router
{
    private Request $request;
    private Response $response;
    private array $routeMap = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get(string $url, $callback)
    {
        $this->routeMap['get'][$url] = $callback;
    }
    public function post(string $url, $callback)
    {
        $this->routeMap['post'][$url] = $callback;
    }

    public function resolve()
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        $callback = $this->routeMap[$method][$url] ?? false;


        $fileExtension = pathinfo($url, PATHINFO_EXTENSION); // let webserver handle static files instead of router
        $staticExtensions = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'ico'];
        
        if (in_array(strtolower($fileExtension), $staticExtensions)) {
            return false;
        }

        if (!$callback) {
            $this->response->statusCode(code: 404);
            // return 'Not Found';
          
        
        //  print_r($this->routeMap)  ;
        
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        if (is_array($callback)) {
            $controller = new $callback[0];
            $controller->action = $callback[1];
            Application::$app->controller = $controller;
            $callback[0] = $controller;

            foreach($controller->getMiddlewares() as $middleware)
            {
                $middleware->execute();
            }

        }
        return call_user_func($callback, $this->request);
    }

    public function renderView($view, $params = [])
    {
        $layoutName = Application::$app->controller ? Application::$app->controller->layout : 'main';
        $viewContent = $this->renderViewOnly($view, $params);
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layoutName.php";
        $layoutContent = ob_get_clean();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderViewOnly($view, $params = [])
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }

    public function renderPartial($view, $params = []): string
{
    foreach ($params as $key => $value) {
        $$key = $value;
    }
    
    ob_start();
    include Application::$ROOT_DIR . "/views/partials/$view.php";
    return ob_get_clean();
}
}