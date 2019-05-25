<?php

namespace cabinet\helpers;

use cabinet\entities\user\Profile;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ProfileHelper
{
    public static function sexList(): array
    {
        return [
            Profile::SEX_MALE => 'Мужчина',
            Profile::SEX_FEMALE => 'Женщина',
        ];
    }

    public static function sexConvertString($value): string
    {
        if($value == Profile::SEX_MALE){
            return 'Мужчина';
        }else{
            return 'Женщина';
        }
    }

    public static function sizeCostumeList(): array
    {
        return ['XXS','XS','S','M','L','XL','XXL'];
    }

    public static function sexLabel($status): string
    {
        switch ($status) {
            case Profile::SEX_MALE:
                $class = 'label label-default';
                break;
            case Profile::SEX_FEMALE:
                $class = 'label label-default';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::sexList(), $status), [
            'class' => $class,
        ]);
    }
}