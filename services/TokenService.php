<?php

namespace App\services;



/**
 * Class TokenService
 * 
 * This class provides services related to token management.
 * 
 * @package Market\Services
 */
 
 
class TokenService
{

    public static function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public static function generateEmailVerificationToken(): string
    {
        return self::generateToken();
    }

    public static function generatePasswordRestToken(): string
    {
        return self::generateToken();
    }

}