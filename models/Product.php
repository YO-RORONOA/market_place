<?php
namespace App\models;

use App\core\Dbmodal\Dbmodal;


class Product extends Dbmodal
{
    public ?int $id = null;
    public string $name = '';
    public string $description = '';
    public float $price = 0.0;
    public int $stock_quantity = 0;
    public int $category_id = 0;
    public int $vendor_id = 0;
    public ?string $image_path = null;
    public string $status = 'active';

    public function tableName(): string
    {
        return 'products';
    }

    public function attributes(): array
    {
        return[
            'name',
            'description',
            'price',
            'stock_quantity',
            'category_id',
            'vendor_id',
            'image_path',
            'status',
        ];
    }

    public function rules(): array
    {
        return[
            'name' =>[self::RULE_REQUIRED],
            'description' =>[self::RULE_REQUIRED],
            'price' =>[self::RULE_REQUIRED],
            'stock_quantity' =>[self::RULE_REQUIRED],
            'category_id' =>[self::RULE_REQUIRED],
            'vendor_id' =>[self::RULE_REQUIRED],
            'status' =>[self::RULE_REQUIRED],
        ];
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}

