<?php

namespace App\core\exception;

class DatabaseException extends \Exception
{
    protected $message = 'A database error occurred';
    protected $code = 500;
}
