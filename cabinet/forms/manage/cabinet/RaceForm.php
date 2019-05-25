<?php

namespace cabinet\forms\manage\cabinet;

use cabinet\entities\cabinet\Race;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;

class RaceForm extends Model
{
    public $name;
    public $description;
    public $photo;
    public $status;
    public $date_start;
    public $date_end;

    private $_race;

    public function __construct(Race $race = null, $config = [])
    {
        if($race){
            $this->name = $race->name;
            $this->status = $race->status;
            $this->date_start = date('d.m.Y', $race->date_start);
            $this->date_end = date('d.m.Y', $race->date_end);
            $this->_race = $race;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'status', 'date_start', 'date_end'], 'required'],
            [['date_start', 'date_end'], 'date', 'format' => 'php:d.m.Y'],
            [['name', 'description'], 'string'],
            ['status', 'integer'],
            [['photo'], 'file', 'extensions' => 'jpeg, png, jpg', /*'on' => ['insert', 'update']*/],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название забега',
            'photo' => 'Фото',
            'status' => 'Статус',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата завершения',
            'description' => 'Краткое описание',
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->photo = UploadedFile::getInstance($this, 'photo');
            return true;
        }
        return false;
    }

    public function upload()
    {
        $width = 300;
        $height = 300;
        $originPath = \Yii::getAlias('@uploadsRoot') . '/origin/race/' . $this->photo->baseName . '.' . $this->photo->extension;
        $thumbPath = \Yii::getAlias('@uploadsRoot') . '/origin/race/' . $this->photo->baseName . "x$width-$height" . '.' . $this->photo->extension;

        if ($this->validate()) {
            $this->photo->saveAs($originPath);
            Image::thumbnail($originPath, $width, $height)->save($thumbPath, ['quality' => 90]);
            return true;
        } else {
            return false;
        }
    }
}