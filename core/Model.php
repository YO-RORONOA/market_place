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
    public const RULE_LETTERS_ONLY = "letters_only"; 
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
                if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL))
                {
                    $this->addError($attribute,self::RULE_REQUIRED);

                }
                if($ruleName === self::RULE_MIN && strlen($value) < $rule['min'])
                {
                    $this->addError($attribute,self::RULE_MIN, $rule);

                }
                if($ruleName === self::RULE_MAX && strlen($value) > $rule['max'])
                {
                    $this->addError($attribute,self::RULE_MAX, $rule);

                }
                if($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']})
                {
                    $this->addError($attribute,self::RULE_MATCH, $rule);

                }
                
                if ($ruleName === self::RULE_LETTERS_ONLY && !preg_match('/^[A-Za-z\s\-\']+$/', $value)) {
                    $this->addError($attribute, self::RULE_LETTERS_ONLY);
                }
            }

    }
    return empty($this->errors);
}

public function addError(string $attribute, string $rule, $params=[])
{
    $message = $this->errorMessages()[$rule] ?? '';
    foreach($params as $key =>$value)
    {
        $message = str_replace("{{$key}}", $value, $message);
    }
    $this->errors[$attribute][] = $message;
}

public function errorMessages()
{
    return [
    self::RULE_REQUIRED => 'this field is required',
    self::RULE_EMAIL => 'this field must be valid email address',
    self::RULE_MIN => 'Min length of this field must be {min}',
    self::RULE_MAX => 'Min length of this field must be {max}',
    self::RULE_MATCH => 'this field must be the same as {match}',
    self::RULE_LETTERS_ONLY => 'This field must contain only letters (no numbers or special characters)'
    ];

}

public function hasError($attribute)
{
    return $this->errors[$attribute] ?? false;
}

public function getFirstError($attribute)
{
    return $this->errors[$attribute][0] ?? false;
}

}