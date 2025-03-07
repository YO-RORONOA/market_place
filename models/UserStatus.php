<?php

namespace App\models;

class UserStatus
{
    public const PENDING = 'pending';
    public const ACTIVE = 'active';
    public const SUSPENDED = 'SUSPENDED';
    public const DELETED = 'deleted';


    public static function getStatuses()
    {
        return[
            self::PENDING,
            self::ACTIVE,
            self::SUSPENDED,
            self::DELETED,
        ];
    }

    public static function isValid(string $status): bool 
    {
        return in_array($status, self::getStatuses());
    }

}