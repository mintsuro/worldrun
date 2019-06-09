<?php

namespace cabinet\entities\cabinet;

use cabinet\entities\user\User;
use cabinet\entities\cabinet\queries\TrackQuery;
use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;

/**
 * @property integer  $id
 * @property integer  $distance
 * @property integer  $pace
 * @property integer  $download_method
 * @property integer  $elapsed_time
 * @property string   $file_screen
 * @property string   $date_start
 * @property integer  $created_at
 * @property integer  $status
 * @property integer  $id_strava_track
 * @property integer  $race_id
 * @property integer  $user_id
 *
 * @property UserAssignment[] $userAssignments
 * @property User $user
 */
class Track extends ActiveRecord
{
    const STRAVA_DOWNLOAD = 1;
    const SCREEN_DOWNLOAD = 2;

    const STATUS_ACTIVE = 10;
    const STATUS_MODERATION = 5;
    const STATUS_WAIT = 0;

    /**
     * @param float $distance
     * @param float $pace
     * @param int $elapsed_time
     * @param string $date_start
     * @param int $id_strava_track
     * @param int $raceId
     * @return Track
     * Created by user
     */
    public static function create(float $distance, float $pace, int $elapsed_time,
        string $date_start, int $id_strava_track, int $raceId): self
    {
        $date = substr($date_start, 0, 10);
        $time = substr($date_start, -9,8);
        $item = new static();
        $item->distance = (int) floor($distance);
        $item->pace = $pace;
        $item->elapsed_time = $elapsed_time;
        $item->download_method = self::STRAVA_DOWNLOAD;
        $item->date_start = $date . ' ' . $time;
        $item->status = self::STATUS_ACTIVE;
        $item->id_strava_track = $id_strava_track;
        $item->user_id = Yii::$app->user->identity->getId();
        $item->race_id = $raceId;
        $item->created_at = time();

        return $item;
    }

    public static function createFromScreen(string $file, int $distance, string $date_start,
        int $elapsed_time, int $raceId): self
    {
        $item = new static();
        $item->file_screen = $file;
        $item->distance = $distance;
        $item->date_start = date('Y-m-d', strtotime($date_start)) . ' 00:00:00';
        $item->elapsed_time = $elapsed_time;
        $item->download_method = self::SCREEN_DOWNLOAD;
        $item->status = self::STATUS_MODERATION;
        $item->user_id = Yii::$app->user->identity->getId();
        $item->race_id = $raceId;
        $item->created_at = time();

        return $item;
    }

    /**
     * @param float $distance
     * @param float $pace
     * @param int $elapsed_time
     * @param int $download_method
     * @param string $file_screen
     * @param string $date_start
     * @param int $status
     * Edit from admin page
     */
    public function edit(float $distance, float $pace, int $elapsed_time,
        int $download_method, string $file_screen, string $date_start, int $status): void
    {
        $this->distance = $distance;
        $this->pace = $pace;
        $this->elapsed_time = $elapsed_time;
        $this->download_method = $download_method;
        $this->file_screen = $file_screen;
        $this->date_start = $date_start;
        $this->status = $status;
    }

    public function setScreen($file){
        $this->file_screen = $file;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('Трек уже активирован.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new \DomainException('Трек уже находится на модерации.');
        }
        $this->status = self::STATUS_MODERATION;
    }

    public function isActive(){
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isDraft(): bool
    {
        return $this->status == self::STATUS_MODERATION;
    }

    ##########################

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    ##########################

    public static function find(): TrackQuery
    {
        return new TrackQuery(static::class);
    }

    public function attributeLabels()
    {
        return [
            'distance' => 'Дистанция',
            'type' => 'Тип забега',
            'download_method' => 'Способ загрузки',
            'elapsed_time' => 'Время пробежки',
            'file_screen' => 'Скриншот',
            'status' => 'Статус',
            'created_at' => 'Дата/время загрузки',
            'date_start' => 'Дата/время старта пробежки',
            'user_id' => 'Пользователь',
            'pace' => 'Темп'
        ];
    }

    public static function tableName()
    {
        return '{{%cabinet_tracks}}';
    }
}