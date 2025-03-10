<?php

namespace App\models;

use App\core\Dbmodal\Dbmodal;


class Category extends Dbmodal
{
    public ?int $id = null;
    public string $name = '';
    public ?int $parent_id = null;

    public function tableName(): string
    {
        return 'categories';
    }

    public function attributes(): array
    {
        return ['name', 'parent_id'];
    }

    public function rules(): array
    {
        return ['name' =>[self::RULE_REQUIRED],];
    }
}
