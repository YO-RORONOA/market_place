<?php


namespace App\models;

use App\core\Dbmodal;


class Role extends Dbmodal
{
    public const BUYER = 1;
    public const VENDOR = 2;
    public const ADMIN = 3;

    public function tableName(): string
    {
        return 'roles';
    }

    public function attributes(): array
    {
        return ['name'];
    }

    public function rules(): array
    {
        return [
            'name' => [self::RULE_REQUIRED]
        ];
    }
}