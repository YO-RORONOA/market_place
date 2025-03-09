<?php

namespace App\core\form;

use App\core\Model;

class Field
{
    public const TYPE_TEXT = "text";
    public const TYPE_PASSWORD = "password";
    public const TYPE_NUMBER = "number";

    public Model $model;
    public string $attribute;
    public string $type;

    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;   
        $this->model = $model;
        $this->attribute = $attribute;
    }

    public function __toString()
    {
        return sprintf(
            '<div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">%s</label>
                <input name="%s" type="%s" value="%s" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent-teal focus:border-accent-teal %s">
                <div class="text-red-500 mt-1 text-sm">
                    %s
                </div>
            </div>',
            $this->attribute,
            $this->attribute,
            $this->type,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? 'border-red-500' : '',
            $this->model->getFirstError($this->attribute)
        );
    }

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function numberField()
    {
        $this->type = self::TYPE_NUMBER;
        return $this;
    }
}