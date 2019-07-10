<?php

namespace cabinet\entities\user;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * @property string first_name
 * @property string last_name
 * @property integer sex
 * @property integer age
 * @property string city
 * @property string phone
 * @property integer postal_code
 * @property string address_delivery
 * @property string city_delivery
 * @property string size_costume
 * @property integer user_id
 */
class Profile extends ActiveRecord
{
    const SEX_FEMALE = 1;
    const SEX_MALE = 2;

    /**
     * Создание записи профиля при регистрации
     * @param string $firstname
     * @return Profile
     */
    public static function createLight(string $firstname): self
    {
        $item = new static();
        $item->first_name = $firstname;

        return $item;
    }

    public function editLight(string $firstname): void
    {
        $this->first_name = $firstname;
    }

    protected  static function calculateAge(string $bDate): string
    {
        try{
            $birthday = date_create($bDate);
            $date = date_create('now');
            $interval = $birthday->diff($date);

            return $interval->format('%y');
        }catch(\InvalidArgumentException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    /**
     * Создание записи профиля при регистрации из соц.сети
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
        $item->age = !empty($profileData['bdate']) ? Profile::calculateAge($profileData['bdate']) : null;
        $item->city = ArrayHelper::getValue($profileData, 'city');
        $item->phone = ArrayHelper::getValue($profileData, 'phone');

        return $item;
    }

    public function edit($firstname, $lastname, $sex, $age, $city, $phone,
        $postal_code, $address_delivery, $city_delivery, $size_costume): void
    {
        $this->first_name = ucfirst($firstname);
        $this->last_name = ucfirst($lastname);
        $this->sex = $sex;
        $this->age = $age;
        $this->city = $city;
        $this->phone = $phone;
        $this->postal_code = $postal_code;
        $this->address_delivery = $address_delivery;
        $this->city_delivery = $city_delivery;
        $this->size_costume = $size_costume;
    }

    public static function tableName(){
        return '{{%user_profile}}';
    }

    public function attributeLabels()
    {
        return [
            'first_name' => 'Имя',
            'last_name'  => 'Фамилия',
            'sex' => 'Пол',
            'age' => 'Возраст',
            'city' => 'Город',
            'phone' => 'Телефон',
            'postal_code' => 'Индекс',
            'address_delivery' => 'Адрес доставки',
            'city_delivery' => 'Город доставки',
            'size_costume' => 'Размер одежды',
        ];
    }

    public function getUser(): ActiveQuery{
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}