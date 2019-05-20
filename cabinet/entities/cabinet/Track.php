<?php

namespace cabinet\entities\cabinet;

use cabinet\entities\user\User;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * @property integer  $id
 * @property integer  $distance
 * @property integer  $type
 * @property integer  $pace
 * @property integer  $download_method
 * @property integer  $time_race
 * @property integer  $created_at
 * @property integer  $user_id
 *
 * @property UserAssignment[] $userAssignments
 */
class Track extends ActiveRecord
{
    ##########################

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    ##########################

    public function attributeLabels(){
        return [
            'distance' => 'Дистанция',
            'type' => 'Тип забега',
            'download_method' => 'Способ загрузки',
            'time_race' => 'Дата/время пробежки',
            'created_at' => 'Дата/время загрузки',
            'user_id' => 'Пользователь',
            'pace' => 'Темп'
        ];
    }

    public static function tableName()
    {
        return '{{%cabinet_tracks}}';
    }
}