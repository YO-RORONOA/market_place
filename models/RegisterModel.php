<?php

namespace App\models;

use App\core\Dbmodal;



class RegisterModel extends Dbmodal
{
    public string $firstname = ''; //attributes accesed before init
    public string $lastname = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirm = '';

    public function tableName(): string
    {
        return 'user';
    }


    public function register()
    {
        echo 'crating new user';
    }

    public function rules()
    {
        return[
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 30]],
            'passwordConfirm' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match'=> 'password']],
        ] ;
    }

}