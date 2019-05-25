<?php

namespace cabinet\forms\shop\order;

use yii\base\Model;

class CustomerForm extends Model
{
    public $firstName;
    public $lastName;
    public $city;
    public $sex;
    public $age;
    public $phone;

    public function rules(): array
    {
        return [
            [['firstName', 'lastName', 'city'], 'required'],
            [['firstName', 'lastName', 'city', 'phone'], 'string', 'max' => 255],
            [['age', 'sex'], 'integer'],

        ];
    }

    public function attributeLabels(){
        return [
            'firstName' => 'Имя',
            'lastName'  => 'Фамилия',
            'city' => 'Город',
            'sex'  => 'Пол',
            'age'  => 'Возраст',
            'phone' => 'Телефон'
        ];
    }
}