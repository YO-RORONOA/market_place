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
    private array $attributes = [];
    private bool $fieldOnly = false;

    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;   
        $this->model = $model;
        $this->attribute = $attribute;
    }

    public function __toString()
    {
        if ($this->fieldOnly) {
            return $this->renderInputOnly();
        }
        
        return sprintf(
            '<div class="form-group">
                <label>%s</label>
                <input name="%s" type="%s" value="%s" class="form-control%s" %s>
                <div class="invalid-feedback">
                    %s
                </div>
            </div>',
            $this->attribute,
            $this->attribute,
            $this->type,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->getAttributesString(),
            $this->model->getFirstError($this->attribute)
        );
    }

    private function renderInputOnly()
    {
        return sprintf(
            '<input name="%s" type="%s" value="%s" %s>',
            $this->attribute,
            $this->type,
            $this->model->{$this->attribute},
            $this->getAttributesString()
        );
    }

    private function getAttributesString(): string
    {
        $attributesString = '';
        foreach ($this->attributes as $key => $value) {
            $attributesString .= "$key=\"$value\" ";
        }
        return $attributesString;
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

    public function fieldOnly()
    {
        $this->fieldOnly = true;
        return $this;
    }

    public function addAttributes(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }
}