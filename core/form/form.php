<?php

namespace App\core\form;

use App\core\Model;



class Form
{
    public static function begin($action, $method, $options = [])
    {
        $class = $options['class'] ?? '';
        echo sprintf('<form action="%s" method="%s" class="%s">', $action, $method, $class);
        return new Form();
    }

    public static function end()
    {
        echo '</form>';
    }

    public function field(Model $model, $attribute)
    {
        return new Field($model, $attribute);
    }
}