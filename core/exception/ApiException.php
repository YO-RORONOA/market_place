<?php

namespace App\core\exception;

class ApiException extends \Exception
{
    protected $details = [];
    

    public function __construct($message = 'API error', $code = 500, array $details = [], \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
    }
    

    public function getDetails(): array
    {
        return $this->details;
    }
}