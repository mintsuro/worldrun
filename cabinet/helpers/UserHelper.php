<?php

namespace cabinet\helpers;

use cabinet\entities\user\User;
use cabinet\entities\cabinet\Race;
use cabinet\entities\cabinet\Track;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserHelper
{
    public static function statusList(): array
    {
        return [
            User::STATUS_INACTIVE => 'В ожидании',
            User::STATUS_ACTIVE => 'Активный',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case User::STATUS_INACTIVE:
                $class = 'label label-default';
                break;
            case User::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    /**
     * @param integer $id
     * @param Race $race
     */
    public static function resultTrack($id, $race)
    {
        $tracks = $race->getTracks();

        if($race->type == Race::TYPE_MULTIPLE){
            $distance = $tracks
                ->andWhere(['user_id' => $id])
                ->andWhere(['status' => Track::STATUS_ACTIVE])
                ->sum('distance');

            return $distance . ' м.';

        }else if($race->type == Race::TYPE_SIMPLE){
            $elapsed_time = $tracks
                ->andWhere(['user_id' => $id])
                ->andWhere(['status' => Track::STATUS_ACTIVE])
                ->sum('elapsed_time');

            return date('H:i:s', strtotime($elapsed_time));
        }

        return '';
    }
}