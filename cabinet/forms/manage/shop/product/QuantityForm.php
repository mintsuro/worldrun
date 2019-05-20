<?php

namespace cabinet\forms\manage\shop\product;

use cabinet\entities\shop\Discount as Discount;
use cabinet\entities\shop\product\Product;
use yii\base\Model;

class QuantityForm extends Model
{
    public $quantity;
    public $name;
    public $value;
    public $sizeProducts;

    public function __construct(Discount $discount = null, array $config = [])
    {
        if($discount){
            $this->quantity = $product->quantity;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['quantity'], 'required'],
            [['quantity'], 'integer', 'min' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'quantity' => 'Количество'
        ];
    }
}