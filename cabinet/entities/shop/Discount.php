<?php

namespace cabinet\entities\shop;

use cabinet\entities\shop\queries\DiscountQuery;
use yii\db\ActiveRecord;

/**
 * @property integer  $id
 * @property string   $name
 * @property integer  $value
 * @property string   $from_date
 * @property string   $to_date
 * @property bool     $active
 * @property integer  $sort
 * @property integer  $type_value
 * @property integer  $type
 * @property integer  $size_products
 * @property string   $code
 */
class Discount extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    const TYPE_SIZE_PROD = 1;
    const TYPE_PROMO_CODE = 2;

    const TYPE_VALUE_NUMBER = 1;
    const TYPE_VALUE_PERCENT = 2;

    public static function create($name, $value, $fromDate, $toDate,
        $typeValue, $type, $sizeProducts, $code): self
    {
        $discount = new static();
        $discount->name = $name;
        $discount->value = $value;
        $discount->from_date = $fromDate;
        $discount->to_date = $toDate;
        $discount->type_value = $typeValue;
        $discount->type = $type;
        $discount->size_products = $sizeProducts;
        $discount->code = $code;
        $discount->active = true;
        $discount->sort = 1;
        return $discount;
    }

    public function edit($name, $value, $fromDate, $toDate,
        $typeValue, $type, $sizeProducts, $code): void
    {
        $this->name = $name;
        $this->value = $value;
        $this->from_date = $fromDate;
        $this->to_date = $toDate;
        $this->type_value = $typeValue;
        $this->type = $type;
        $this->size_products = $sizeProducts;
        $this->code = $code;
    }

    public function activate(): void
    {
        $this->active = true;
    }

    public function draft(): void
    {
        $this->active = false;
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function isActive(): bool
    {
        return $this->active == self::STATUS_ACTIVE;
    }

    public static function tableName(): string
    {
        return '{{%shop_discounts}}';
    }

    public static function find(): DiscountQuery
    {
        return new DiscountQuery(static::class);
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'value' => 'Значение',
            'size_products' => 'От количества выбранных товаров',
            'from_date' => 'Действует с выбранной даты',
            'to_date' => 'Заканчивается в выбранную дату',
            'active' => 'Статус',
            'type_value' => 'Значение типа скидки',
            'type' => 'Тип скидки',
            'sort' => 'Сортировка',
        ];
    }
}