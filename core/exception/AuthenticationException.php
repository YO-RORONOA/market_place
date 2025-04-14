<?php


namespace App\core\exception;

class AuthenticationException extends \Exception
{
    protected $message = 'Authentication failed';
    protected $code = 401;
}