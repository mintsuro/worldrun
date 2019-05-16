<?php

namespace cabinet\forms\shop\order;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use cabinet\helpers\PriceHelper;

class DeliveryForm extends Model
{
    public $index;
    public $address;

    public function rules(): array
    {
        return [
           [['index', 'address'], 'required'],
           [['index'], 'string', 'max' => 255],
           [['address'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'index' => 'Почтовый индекс',
            'address' => 'Адрес доставки',
        ];
    }
}