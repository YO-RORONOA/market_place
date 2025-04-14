<?php

namespace App\core\exception;


class BadRequestException extends \Exception
{
    protected $code = 400;
}