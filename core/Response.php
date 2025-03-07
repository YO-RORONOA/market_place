<?php

namespace App\core;



class Response
{
    public function statusCode(int $code)
    {
        http_response_code($code);
    }


    public function redirect(string $url)
    {
        header("location: $url");
        exit;
    }
}