<?php

namespace cabinet\forms\user;

use cabinet\entities\user\Profile;
use yii\base\Model;

class ProfileEditForm extends Model
{
    public $first_name;
    public $last_name;
    public $sex;
    public $age;
    public $city;
    public $phone;
    public $postal_code;
    public $address_delivery;
    public $size_costume;

    private $_profile;

    public function __construct(Profile $profile, array $config = [])
    {
        $this->first_name = $profile->first_name;
        $this->last_name = $profile->last_name;
        $this->sex = $profile->sex;
        $this->age = $profile->age;
        $this->city = $profile->city;
        $this->phone = $profile->phone;
        $this->postal_code = $profile->postal_code;
        $this->address_delivery = $profile->address_delivery;
        $this->size_costume = $profile->size_costume;
        $this->_profile = $profile;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['first_name', 'last_name', 'sex', 'age', 'city', 'phone'], 'required'],
            [['first_name', 'last_name', 'age', 'city', 'phone', 'address_delivery', 'size_costume'], 'string', 'max' => 255],
            [['age'], 'integer', 'min' => 5, 'max' => 99],
            [['phone'], 'string', 'max' => 20],
            [['sex', 'postal_code'], 'integer'],
            //['phone', 'match', 'pattern' => '/^\+7\([0-9]{3}\)\[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'message' => 'Неправильный формат телефонного номера']
        ];
    }

    public function attributeLabels(){
        return [
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'sex' => 'Пол',
            'age' => 'Возраст',
            'city' => 'Город',
            'phone' => 'Телефон',
            'postal_code' => 'Индекс',
            'address_delivery' => 'Адрес доставки',
            'size_costume' => 'Размер одежды',
        ];
    }
}