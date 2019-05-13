<?php

namespace cabinet\repositories\shop;

use cabinet\entities\shop\product\Product;
use cabinet\repositories\NotFoundException;

class ProductRepository
{
    public function get($id): Product
    {
        if (!$product = Product::findOne($id)) {
            throw new NotFoundException('Продукт не найден.');
        }
        return $product;
    }

    public function save(Product $product): void
    {
        if (!$product->save()) {
            throw new \RuntimeException('Ошибка при сохранении.');
        }
    }

    public function remove(Product $product): void
    {
        if (!$product->delete()) {
            throw new \RuntimeException('Ошибка при удалении.');
        }
    }
}