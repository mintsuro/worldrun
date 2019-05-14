<?php

namespace cabinet\forms\shop\order;

class CustomerForm
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
            [['firstName', 'lastName', 'city', 'sex', 'age'], 'required'],
            [['firstName', 'lastName', 'city', 'phone'], 'string', 'max' => 255],
            [['sex', 'age'], 'integer'],
        ];
    }
}