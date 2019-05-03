<?php

namespace cabinet\entities\cabinet;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * @property integer $id
 * @property string  $name
 * @property string  $photo
 * @property integer $status
 * @property integer $date_start
 * @property integer $date_end
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

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->photo = UploadedFile::getInstance($this, 'photo');
            return true;
        }
        return false;
    }
}