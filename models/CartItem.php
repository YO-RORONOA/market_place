<?php

namespace App\models;

class CartItem
{
    public int $product_id;
    public string $name;
    public float $price;
    public int $quantity;
    public ?string $image_path;
    
    public function __construct(int $product_id, string $name, float $price, int $quantity, ?string $image_path = null)
    {
        $this->product_id = $product_id;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->image_path = $image_path;
    }
    
    public function getTotal(): float
    {
        return $this->price * $this->quantity;
    }
}