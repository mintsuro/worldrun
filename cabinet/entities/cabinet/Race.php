<?php

namespace cabinet\entities\cabinet;

use cabinet\entities\user\User;
use cabinet\forms\manage\cabinet\TemplateForm;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use cabinet\entities\cabinet\StartNumber;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use cabinet\entities\cabinet\queries\RaceQuery;
use yii\db\ActiveQuery;
use DateTime;
//use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $id
 * @property string  $name
 * @property string $description
 * @property string  $photo
 * @property integer $status
 * @property integer $date_start
 * @property integer $date_end
 * @property integer $type
 *
 * @property UserAssignment[] $userAssignments
 * @property PdfTemplate $template
 * @property StartNumber $startnumber
 */
class Race extends ActiveRecord
{
    const STATUS_REGISTRATION = 5;
    const STATUS_WAIT = 10;
    const STATUS_COMPLETE = 20;

    const TYPE_MULTIPLE = 1;
    const TYPE_SIMPLE = 2;

    public static function create(string $name, string $description, int $status,
        string $date_start, string $date_end, int $type): self
    {
        $item = new static();
        $item->name = $name;
        $item->description = $description;
        $item->status = $status;
        $item->date_start = date('Y-m-d', strtotime($date_start)) . ' 00:00:00';
        $item->date_end = date('Y-m-d', strtotime($date_end)) . '  23:59:59';
        $item->type = $type;

        return $item;
    }

    public function edit(string $name, string $description, int $status,
         string $date_start, string $date_end, int $type): void
    {
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
        $this->date_start = date('Y-m-d', strtotime($date_start)) . ' 00:00:00';
        $this->date_end = date('Y-m-d', strtotime($date_end)) . ' 23:59:59';
        $this->type = $type;
    }

    public function setPhoto(UploadedFile $photo): void
    {
        $this->photo = $photo;
    }

    public function addTemplate(TemplateForm $form): void
    {
        $template = PdfTemplate::create(
            $form->start_number, $form->diploma,
            $form->top_start_number, $form->top_diploma);
        $this->template = $template;
    }

    public function editTemplate($raceId, TemplateForm $form): void
    {
        $item = PdfTemplate::findOne(['race_id' => $raceId]);
        $item->edit($form->start_number, $form->diploma,
            $form->top_start_number, $form->top_diploma, $raceId);
        $item->save();
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

    /**
     * @param int $userId
     * @param int $raceId
     */
    public function createStartNumber(int $raceId, int $userId)
    {
        $startNumber = StartNumber::create($raceId, $userId);
        $startNumber->save();
        //\Yii::$app->db->createCommand('INSERT INTO `cabinet_user_startnumber` (`user_id`) VALUES (`1`)')->execute();
    }

    public function getIntervalDate(){
        $date_from = new DateTime($this->date_start);
        $date_to = new  DateTime($this->date_end);
        $interval = $date_from->diff($date_to);
        return $interval->format('%a');
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

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->via('userAssignments');
    }

    public function getTracks(): ActiveQuery
    {
        return $this->hasMany(Track::class, ['race_id' => 'id']);
    }

    public function getTemplate(): ActiveQuery
    {
        return $this->hasOne(PdfTemplate::class, ['race_id' => 'id']);
    }

    public function getStartnumber(): ActiveQuery
    {
        return $this->hasOne(StartNumber::class, ['race_id' => 'id']);
    }

    ##########################

    public function attributeLabels()
    {
        return [
            'name' => 'Название забега',
            'description' => 'Краткое описание',
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
                'relations' => ['userAssignments', 'template'],
            ],
            /* [
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
            ], */
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