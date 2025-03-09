<?php

namespace App\models;

use App\core\Model;



class LoginForm extends Model
{
    public string $email = '';
    public string $password = '';
    public bool $rememberMe = false;

    public function rules(): array
    {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED]
        ];
    }

    public function labels(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
            'rememberMe' => 'Remember me'
        ];
    }
}