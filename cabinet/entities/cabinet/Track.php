<?php

namespace cabinet\entities\cabinet;

use cabinet\entities\user\User;
use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * @property integer  $id
 * @property integer  $distance
 * @property integer  $pace
 * @property integer  $download_method
 * @property integer  $elapsed_time
 * @property string   $date_start
 * @property integer  $created_at
 * @property integer  $status
 * @property integer  $id_strava_track
 * @property integer  $race_id
 * @property integer  $user_id
 *
 * @property UserAssignment[] $userAssignments
 */
class Track extends ActiveRecord
{
    const STRAVA_DOWNLOAD = 1;
    const SCREEN_DOWNLOAD = 2;

    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 0;


    public static function create(float $distance, float $pace, int $elapsed_time,
        int $download_method, string $date_start, int $id_strava_track, int $raceId): self
    {
        $date = substr($date_start, 0, 10);
        $time = substr($date_start, -9,8);
        $item = new static();
        $item->distance = (int) floor($distance);
        $item->pace = $pace;
        $item->elapsed_time = $elapsed_time;
        $item->download_method = $download_method;
        $item->date_start = $date . ' ' . $time;
        $item->created_at = time();
        $item->status = self::STATUS_ACTIVE;
        $item->id_strava_track = $id_strava_track;
        $item->user_id = Yii::$app->user->identity->getId();
        $item->race_id = $raceId;

        return $item;
    }

    ##########################

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    ##########################

    public function attributeLabels()
    {
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