<?php
namespace cabinet\helpers;

use cabinet\entities\shop\product\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use cabinet\cart\Cart;
use yii\helpers\Url;

class ProductHelper
{
    public static function statusList(): array
    {
        return [
            Product::STATUS_DRAFT => 'В ожидании',
            Product::STATUS_ACTIVE => 'Активный',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Product::STATUS_DRAFT:
                $class = 'label label-default';
                break;
            case Product::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', self::statusName($status), [
            'class' => $class,
        ]);
    }

    public static function buttonCart(Cart $cart, Product $model): string
    {
        $check = false;
        $product_options = [
            'class' => 'btn-choice add-cart',
            'data-url' =>  Url::to(['/shop/cart/add', 'id' => $model->id]),
            'data-product-id' => $model->id,
            'data' => ['method' => 'post'],
        ];

        foreach($cart->getItems() as $item){
            if($item->getProductId() == $model->id ){
                $check = true;
                $product_options = [
                    'class' => 'btn-choice remove-cart active',
                    'data-url' => Url::to(['/shop/cart/remove', 'id' => $item->getId()]),
                    'data-product-id' => $model->id,
                    'data-id' => $item->getId(),
                    'data' => ['method' => 'post'],
                ];
               break;
            }
        }

        return Html::tag('span', (!$check) ? 'Выбрать' : 'Выбрано', $product_options)
            . "<div class='loader'></div>";
    }
}