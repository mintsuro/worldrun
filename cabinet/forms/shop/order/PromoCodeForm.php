<?php

namespace cabinet\forms\shop\order;

use yii\base\Model;

class PromoCodeForm extends Model
{
    public $code;

    public function rules(){
        return [
            //[['code'], 'required'],
            [['code'], 'string', 'min' => 3, 'max' => 10],
        ];
    }

    public function attributeLabels()
    {
        return ['code' => 'Промокод'];
    }
}