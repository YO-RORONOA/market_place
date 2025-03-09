<?php

namespace App\models;

use App\core\Model;

class PasswordResetForm extends Model
{
    public string $password = '';
    public string $passwordConfirm = '';

    public function rules(): array
    {
        return [
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'passwordConfirm' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']]
        ];
    }

    public function labels(): array
    {
        return [
            'password' => 'New Password',
            'passwordConfirm' => 'Confirm New Password'
        ];
    }
}