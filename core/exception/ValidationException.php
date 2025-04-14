<?php


namespace App\core\exception;

class ValidationException extends \Exception
{
    
    protected $errors = [];
    
    public function __construct($message = 'Validation failed', array $errors = [], $code = 422, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }
    
    public function getErrors(): array
    {
        return $this->errors;
    }
}