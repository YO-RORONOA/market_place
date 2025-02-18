<?php

namespace App\core\form;

use App\core\Model;


class Field
{
    public Model $model;
    public string $attribute;

    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }


    public function __tostring()
    {
        return sprintf(
            '<div class="form-group">
        <label>%s</label>
        <input name="%s" type="text" value="%s" class="form-control%s">
        <div class= "invalid-feedback">
            $s
            </div>
            </div>
            
            ',
            $this->attribute,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ?'is-invalid':'',
            $this->model->getFirstError($this->attribute)

        );
    }
}