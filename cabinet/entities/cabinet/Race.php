<?php

namespace cabinet\entities\cabinet;

use cabinet\entities\shop\product\Product;
use cabinet\entities\user\User;
use cabinet\entities\shop\order\Order;
use cabinet\forms\manage\cabinet\TemplateForm;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\UploadedFile;
use cabinet\entities\cabinet\queries\RaceQuery;
use yii\db\ActiveQuery;
use DateTime;

/**
 * @property integer $id
 * @property string  $name
 * @property string $description
 * @property integer $status
 * @property integer $date_start
 * @property integer $date_end
 * @property string  $photo
 * @property boolean $strava_only
 * @property string  $date_reg_from
 * @property string  $date_reg_to
 * @property integer $type
 *
 * @property UserAssignment[] $userAssignments
 * @property PdfTemplate $template
 * @property Order $order
 * @property User $user
 * @property User[] $users
 * @property Track[] $tracks
 * @property Product[] $products
 */
class Race extends ActiveRecord
{
    const STATUS_REGISTRATION = 5;
    const STATUS_WAIT = 10;
    const STATUS_COMPLETE = 20;

    const TYPE_MULTIPLE = 1;
    const TYPE_SIMPLE = 2;

    public static function create(string $name, string $description, int $status, int $strava_only,
        string $date_start, string $date_end, string $date_reg_from, string $date_reg_to, int $type): self
    {
        $item = new static();
        $item->name = $name;
        $item->description = $description;
        $item->status = $status;
        $item->strava_only = $strava_only;
        $item->date_start = date('Y-m-d', strtotime($date_start)) . ' 00:00:00';
        $item->date_end = date('Y-m-d', strtotime($date_end)) . '  23:59:59';
        $item->date_reg_from = date('Y-m-d', strtotime($date_reg_from)) . ' 00:00:00';
        $item->date_reg_to = date('Y-m-d', strtotime($date_reg_to)) . '  23:59:59';
        $item->type = $type;

        return $item;
    }

    public function edit(string $name, string $description, int $status, int $strava_only,
         string $date_start, string $date_end, string $date_reg_from, string $date_reg_to, int $type): void
    {
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
        $this->strava_only = $strava_only;
        $this->date_start = date('Y-m-d', strtotime($date_start)) . ' 00:00:00';
        $this->date_end = date('Y-m-d', strtotime($date_end)) . ' 23:59:59';
        $this->date_reg_from = date('Y-m-d', strtotime($date_reg_from)) . ' 00:00:00';
        $this->date_reg_to = date('Y-m-d', strtotime($date_reg_to)) . '  23:59:59';
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
            $form->top_diploma);
        $this->template = $template;
    }

    public function editTemplate($raceId, TemplateForm $form): void
    {
        $item = PdfTemplate::findOne(['race_id' => $raceId]);
        $item->edit($form->start_number, $form->diploma,
            $form->top_diploma, $raceId);
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
        //$assignment = UserAssignment::create($userId, $raceId);
        //$assignment->save();
        /*\Yii::$app->db->createCommand("INSERT INTO cabinet_user_participation (race_id, user_id, start_number) VALUES (
          $raceId,
          $userId,
          (CASE start_number WHEN race_id = $raceId THEN SELECT MAX(start_number)+1 FROM cabinet_user_participation ELSE 1 END)
        )")->execute(); */
        $count = \Yii::$app->db->createCommand("SELECT * FROM cabinet_user_participation WHERE race_id = $raceId")->execute();

        if($count !== 0){
            $res = \Yii::$app->db->createCommand("SELECT MAX(start_number) FROM cabinet_user_participation WHERE race_id = $raceId")->execute();
        }else{
            $res = 0;
        }

        \Yii::$app->db->createCommand("INSERT INTO cabinet_user_participation (race_id, user_id, start_number) VALUES ($raceId, $userId, $res + 1)")->execute();
    }

    public function getIntervalDate()
    {
        $date_from = new DateTime($this->date_start);
        $date_to = new  DateTime($this->date_end);
        $interval = $date_from->diff($date_to);
        return $interval->format('%a');
    }

    /**
     * @param $race Race model
     * @return string
     */
    public function getStartNumberForUser(Race $race): string
    {
        $assignments = $race->userAssignments;
        /** @var  $assign UserAssignment */
        foreach($assignments as $assign){
            if($assign->user_id === \Yii::$app->user->getId()){
                return $assign->start_number;
            }
        }
        return null;
    }

    /** Проверка на количество загруженных треков с типом одиночный забег
     * @param integer $userId
     * @return boolean
     */
    public function getCountSimpleTrack($userId): bool
    {
        $flag = true;

        if($this->type == self::TYPE_SIMPLE):
            $count = $this->getTracks()->andWhere(['user_id' => $userId, 'race_id' => $this->id])->count();
            if($count >= 1){
                $flag = false;
                return $flag;
            }
        endif;

        return $flag;
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

    public function getOrder(): ActiveQuery
    {
        return $this->hasOne(Order::class, ['race_id' => 'id']);
    }

    public function getProducts(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['race_id' => 'id']);
    }

    ##########################

    public function attributeLabels()
    {
        return [
            'name' => 'Название забега',
            'description' => 'Краткое описание',
            'status' => 'Статус',
            'photo' => 'Изображение',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата завершения',
            'date_reg_from' => 'Дата начала регистрации',
            'date_reg_to' => 'Дата окончания регистрации',
            'strava_only' => 'Загрузка только для Strava',
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
            [
                'class' => '\yiidreamteam\upload\ImageUploadBehavior',
                'attribute' => 'photo',
                'thumbs' => [
                    'thumb' => ['width' => 400, 'height' => 400],
                ],
                'filePath' => \Yii::getAlias('@uploadsRoot') . '/origin/race/[[pk]]-[[basename]]',
                'fileUrl' => \Yii::$app->get('frontendUrlManager')->baseUrl . '/uploads/origin/race/[[pk]]-[[basename]]',
                'thumbPath' => \Yii::getAlias('@uploadsRoot') . '/thumb/race/[[pk]]-[[basename]]',
                'thumbUrl' => \Yii::$app->get('frontendUrlManager')->baseUrl . '/uploads/thumb/race/[[pk]]-[[basename]]',
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