<?php

namespace cabinet\entities\user;

use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 * @property string first_name
 * @property string last_name
 * @property integer sex
 * @property integer age
 * @property string city
 * @property string phone
 * @property integer postal_code
 * @property string address_delivery
 * @property string size_costume
 * @property integer user_id
 */
class Profile extends ActiveRecord
{
    const SEX_FEMALE = 1;
    const SEX_MALE = 2;

    /**
     * Create in base signup data
     * @param string $firstname
     * @return Profile
     */
    public static function createLight(string $firstname): self
    {
        $item = new static();
        $item->first_name = $firstname;

        return $item;
    }

    /**
     * Create in network data
     * @param string $profileData
     * @return Profile
     */
    public static function create(string $profileData): self
    {
        $profileData = Json::decode($profileData);
        $item = new static();
        $item->first_name = ArrayHelper::getValue($profileData, 'first_name');
        $item->last_name = ArrayHelper::getValue($profileData,'last_name');
        $item->sex = ArrayHelper::getValue($profileData, 'sex');
        $item->age = ArrayHelper::getValue($profileData, 'bdate');
        $item->city = ArrayHelper::getValue($profileData, 'city');
        $item->phone = ArrayHelper::getValue($profileData, 'phone');

        return $item;
    }

    public function edit($firstname, $lastname, $sex, $age, $city, $phone,
        $postal_code, $address_delivery, $size_costume): void
    {
        $this->first_name = $firstname;
        $this->last_name = $lastname;
        $this->sex = $sex;
        $this->age = $age;
        $this->city = $city;
        $this->phone = $phone;
        $this->postal_code = $postal_code;
        $this->address_delivery = $address_delivery;
        $this->size_costume = $size_costume;
    }

    public static function tableName(){
        return '{{%user_profile}}';
    }
}