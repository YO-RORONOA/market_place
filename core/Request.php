<?php


namespace App\core;

/**
 * Handles HTTP requests and provides methods to access request data.
 * Part of the Market core.
 */


class Request
{
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    
    public function isGet()
    {
        return $this->getMethod() === 'get';
    }
    public function isPost()
    {
        return $this->getMethod() === 'post';
    }


    public function getUrl()
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }


    public function getbody()
    {
        $body = [];
        if($this->getMethod()==='get')
        {
            foreach($_GET as $key => $value)
            {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if($this->getMethod()==='post')
        {
            foreach($_POST as $key => $value)
            {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }

    public function getQuery(string $key = null)
    {
        $queryParams = [];
        
        $queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? '';
        parse_str($queryString, $queryParams);
        
        if ($key !== null) {
            return $queryParams[$key] ?? null;
        }
        
        return $queryParams;
    }

    /**
     * Retrieves the host information from the request.
     *
     * @return string The host information.
     */
    
    public function getHostInfo(): string
    {
        $isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
        $protocol = $isHttps ? 'https' : 'http';

        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
        
        return $protocol . '://' . $host;
    }
}