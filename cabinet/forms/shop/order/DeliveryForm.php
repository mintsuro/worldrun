<?php

namespace cabinet\forms\shop\order;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use cabinet\helpers\PriceHelper;

class DeliveryForm extends Model
{
    public $index;
    public $address;
    public $city;

    public function rules(): array
    {
        return [
           [['index', 'address', 'city'], 'required'],
           [['index', 'address', 'city'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'index' => 'Почтовый индекс',
            'address' => 'Адрес доставки',
            'city' => 'Город доставки',
        ];
    }
}