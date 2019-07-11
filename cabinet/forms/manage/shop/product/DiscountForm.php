<?php

namespace cabinet\forms\manage\shop\product;

use yii\base\Model;
use cabinet\entities\shop\Discount as Discount;

class DiscountForm extends Model
{
    public $name;
    public $value;
    public $fromDate;
    public $toDate;
    public $typeValue;
    public $type;
    public $sizeProducts;
    public $code;

    public function __construct(Discount $discount = null, $config = [])
    {
        if($discount) {
            $this->name = $discount->name;
            $this->value = $discount->value;
            $this->fromDate = date('d.m.Y', strtotime($discount->from_date));
            $this->toDate = date('d.m.Y', strtotime($discount->to_date));
            $this->type = $discount->type;
            $this->typeValue = $discount->type_value;
            $this->sizeProducts = $discount->size_products;
            $this->code = $discount->code;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'value', 'type', 'typeValue'], 'required'],
            [['type', 'typeValue', 'value', 'sizeProducts'] , 'integer'],
            [['fromDate', 'toDate'], 'string'],
            [['name', 'code'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'value' => 'Значение',
            'sizeProducts' => 'Активна для выбранного количества товаров',
            'fromDate' => 'Действует с выбранной даты',
            'toDate' => 'Заканчивается в выбранную дату',
            'active' => 'Статус',
            'typeValue' => 'Значение типа скидки',
            'type' => 'Тип скидки',
            'sort' => 'Сортировка',
            'code' => 'Код для промокода',
        ];
    }
}