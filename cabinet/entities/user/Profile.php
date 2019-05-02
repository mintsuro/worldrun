<?php

namespace cabinet\entities\user;

use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 * @property string first_name
 * @property string last_name
 * @property string sex
 * @property integer age
 * @property string city
 * @property string phone
 * @property integer postal_code
 * @property string address_delivery
 */
class Profile extends ActiveRecord
{
    public static function create(string $profileData): self
    {
        $profileData = Json::decode($profileData);
        $item = new static();
        $item->first_name = ArrayHelper::getValue($profileData, 'first_name');
        $item->age = ArrayHelper::getValue($profileData, 'bdate');
        $item->city = ArrayHelper::getValue($profileData, 'city');
        $item->phone = ArrayHelper::getValue($profileData, 'phone');

        return $item;
    }

    public static function tableName(){
        return '{{%user_profile}}';
    }
}