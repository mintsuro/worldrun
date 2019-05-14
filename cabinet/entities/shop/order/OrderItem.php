<?php

namespace cabinet\entities\shop\order;

use yii\db\ActiveRecord;
use cabinet\entities\shop\product\Product;

/**
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $product_name
 * @property int $price
 */
class OrderItem extends ActiveRecord
{
    public static function create(Product $product, $price)
    {
        $item = new static();
        $item->product_id = $product->id;
        $item->product_name = $product->name;
        $item->price = $price;
        return $item;
    }

    public function getCost(): int
    {
        return $this->price;
    }

    public static function tableName(): string
    {
        return '{{%shop_order_items}}';
    }
}