<?php

namespace cabinet\cart;

use cabinet\entities\shop\product\Product;

class CartItem
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getId(): string
    {
        return md5(serialize([$this->product->id]));
    }

    public function getProductId(): int
    {
        return $this->product->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getPrice(): int
    {
        return $this->product->price;
    }

    public function getCost(): int
    {
        return $this->getPrice();
    }

    public function plus()
    {
        return new static($this->product);
    }
}