<?php

namespace cabinet\entities\shop\product\events;

use cabinet\entities\shop\product\Product;

class ProductAppearedInStock
{
    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}