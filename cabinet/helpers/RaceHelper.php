<?php

namespace cabinet\helpers;

use cabinet\entities\cabinet\Race;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class RaceHelper
{
    public static function statusList(): array
    {
        return [
            Race::STATUS_REGISTRATION => 'Регистрация',
            Race::STATUS_WAIT => 'В процессе',
            Race::STATUS_COMPLETE => 'Завершен'
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Race::STATUS_REGISTRATION:
                $class = 'label label-default';
                break;
            case Race::STATUS_WAIT:
                $class = 'label label-default';
                break;
            case Race::STATUS_COMPLETE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }
}