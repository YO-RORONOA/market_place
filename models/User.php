<?php

namespace App\models;

use App\core\Dbmodal;



class User extends Dbmodal
{
    public string $firstname = ''; //attributes accesed before init
    public string $lastname = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirm = '';
    public int $role_id = Role::BUYER;
    public string $status = UserStatus::PENDING;
    public ? string $verification_token = null;
    public ?string $email_verified_at = null;


    public function tableName(): string
    {
        return 'user';
    }

    public function attributes(): array
    {
        return['firstname', 'lastname', 'email', 'password',
         'role_id', 'status', 'verification_token'];
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
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 30]],
            'passwordConfirm' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match'=> 'password']],
            'role_id' => [self::RULE_REQUIRED],
            'status' => [self::RULE_REQUIRED]
        ] ;
    }

    public function save(): bool  
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    public function getDisplayName(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::ACTIVE
    }

}