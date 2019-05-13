<?php

namespace cabinet\entities\cabinet;

use cabinet\entities\user\User;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use cabinet\entities\cabinet\queries\RaceQuery;
use yii\db\ActiveQuery;
//use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string  $name
 * @property string  $photo
 * @property integer $status
 * @property integer $date_start
 * @property integer $date_end
 *
 * @property UserAssignment[] $userAssignments
 */
class Race extends ActiveRecord
{
    const STATUS_REGISTRATION = 5;
    const STATUS_WAIT = 10;
    const STATUS_COMPLETE = 20;

    public static function create(string $name, int $status,
        string $date_start, string $date_end): self
    {
        $item = new static();
        $item->name = $name;
        $item->status = $status;
        $item->date_start = strtotime($date_start);
        $item->date_end = strtotime($date_end);

        return $item;
    }

    public function edit(string $name, int $status,
        string $date_start, string $date_end): void
    {
        $this->name = $name;
        $this->status = $status;
        $this->date_start = strtotime($date_start);
        $this->date_end = strtotime($date_end);
    }

    public function setPhoto(UploadedFile $photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @param integer $userId
     * @param integer $raceId
     */
    public function assignUser(int $userId, int $raceId)
    {
        $assignments = $this->userAssignments;
        foreach($assignments as $assignment){
            if($assignment->isForUser($userId)){
                throw new \DomainException('Пользователь уже зарегистрирован');
            }
        }
        $assignment = UserAssignment::create($userId, $raceId);
        $assignment->save();
    }

    ##########################

    public function getUserAssignments(): ActiveQuery
    {
        return $this->hasMany(UserAssignment::class, ['race_id' => 'id']);
    }

    public function getUsers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->via('userAssignments');
    }

    ##########################

    public function attributeLabels()
    {
        return [
            'name' => 'Название забега',
            'photo' => 'Фото',
            'status' => 'Статус',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата завершения',
        ];
    }

    public static function tableName()
    {
        return '{{%cabinet_race}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['userAssignments'],
            ],
            [
                'class' => \mohorev\file\UploadImageBehavior::class,
                'attribute' => 'photo',
                'scenarios' => ['insert', 'update'],
                'placeholder' => '@app/modules/user/assets/images/userpic.jpg',
                'path' => \Yii::getAlias('@uploadsRoot') . '/origin/race',
                'url' => \Yii::$app->get('frontendUrlManager')->baseUrl . '/uploads/origin/race',
                'thumbPath' => '@webroot/upload/{id}/images/thumb',
                'thumbUrl' => '@web/upload/{id}/images/thumb',
                'thumbs' => [
                    'thumb' => ['width' => 640, 'height' => 480],
                    'preview' => ['width' => 100, 'height' => 70],
                ],
            ],
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find(): RaceQuery
    {
        return new RaceQuery(static::class);
    }
}