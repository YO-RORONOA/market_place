<?php


namespace App\core;



abstract class Model
{
    public const RULE_REQUIRED = "required";
    public const RULE_EMAIL= "email";
    public const RULE_MIN = "min";
    public const RULE_MAX = "max";
    public const RULE_MATCH = "match";
    public const RULE_UNIQUE = "unique";
    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules();
    public array $errors = [];
    public function validate()
    {
        foreach($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach($rules as $rule)
            {
                $ruleName = $rule;
                if (!is_string($rule)) {
                    $ruleName = $rule[0];
                }
                if($ruleName === self::RULE_REQUIRED && !$value)
                {
                    $this->addError($attribute,self::RULE_REQUIRED);
                }
            }

    }
    return empty($this->errors);
}

public function addError(string $attribute, string $rule)
{
    $message = $this->errorMessages()[$rule] ?? '';
    $this->errors[$attribute][] = $message;
}

public function errorMessages()
{
    return [
    self::RULE_REQUIRED => 'this field is required',
    self::RULE_EMAIL => 'this field must be valid email address',
    self::RULE_MIN => 'Min length of this field must be {mix}',
    self::RULE_MAX => 'Min length of this field must be {max}',
    self::RULE_MATCH => 'this field must be the same as {match}',
    ];

}

}