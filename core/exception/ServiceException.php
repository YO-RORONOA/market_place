<?php



namespace App\core\exception;


class ServiceException extends \Exception
{
    protected $message = 'Service error occurred';
    protected $code = 500;
}