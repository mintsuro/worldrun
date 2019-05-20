<?php

namespace cabinet\helpers;

use cabinet\entities\shop\Discount;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class DiscountHelper
{
    public static function statusList(): array
    {
        return [
            Discount::STATUS_DRAFT => 'В ожидании',
            Discount::STATUS_ACTIVE => 'Активный',
        ];
    }

    public static function typeValueList(): array
    {
        return [
            Discount::TYPE_VALUE_NUMBER => 'Числовой',
            Discount::TYPE_VALUE_PERCENT => 'Процентный',
        ];
    }

    public static function typeList(): array
    {
        return [
            Discount::TYPE_SIZE_PROD => 'Скидка на количество выбранных товаров',
            Discount::TYPE_PROMO_CODE => 'Промокод',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function typeName($type): string
    {
        return ArrayHelper::getValue(self::typeList(), $type);
    }

    public static function typeValueName($type): string
    {
        return ArrayHelper::getValue(self::typeValueList(), $type);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Discount::STATUS_DRAFT:
                $class = 'label label-default';
                break;
            case Discount::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', self::statusName($status), [
            'class' => $class,
        ]);
    }

    public static function typeLabel($type){
        return Html::tag('span', self::typeName($type), [
            'class' => 'label label-default'
        ]);
    }

    public static function typeValueLabel($type){
        return Html::tag('span', self::typeValueName($type), [
            'class' => 'label label-success'
        ]);
    }
}