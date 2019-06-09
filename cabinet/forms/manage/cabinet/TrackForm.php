<?php

namespace cabinet\forms\manage\cabinet;

use cabinet\entities\cabinet\Track;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * @property TemplateForm $template
 */
class TrackForm extends Model
{
    public $distance;
    public $pace;
    public $elapsed_time;
    public $download_method;
    public $file_screen;
    public $date_start;
    public $status;

    private $_track;

    public function __construct(Track $track = null, $config = [])
    {
        if($track){
            $this->distance = $track->distance;
            $this->pace = $track->pace;
            $this->elapsed_time = $track->elapsed_time;
            $this->date_start = date('d.m.Y', strtotime($track->date_start));
            $this->download_method = $track->download_method;
            $this->_track = $track;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['distance', 'download_method', 'elapsed_time', 'date_start'], 'required'],
            [['date_start'], 'date', 'format' => 'php:d.m.Y'],
            [['distance', 'pace', 'elapsed_time', 'download_method'], 'integer'],
            [['file_screen'], 'file', 'extensions' => 'jpeg, png, jpg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'distance' => 'Дистанция',
            'type' => 'Тип забега',
            'download_method' => 'Способ загрузки',
            'created_at' => 'Дата/время загрузки',
            'date_start' => 'Дата/время пробежки',
            'user_id' => 'Пользователь',
            'pace' => 'Темп'
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->file_screen = UploadedFile::getInstance($this, 'file_screen');
            return true;
        }
        return false;
    }

    public function upload()
    {
        $width = 1024;
        $height = 768;
        $originPath = \Yii::getAlias('@uploadsRoot') . '/origin/screen/' . $this->file_screen->baseName . '.' . $this->file_screen->extension;
        $thumbPath = \Yii::getAlias('@uploadsRoot') . '/thumb/screen/' . $this->file_screen->baseName . "x$width-$height" . '.' . $this->file_screen->extension;

        if ($this->validate()) {
            $this->file_screen->saveAs($originPath);
            Image::thumbnail($originPath, $width, $height)->save($thumbPath, ['quality' => 90]);
            return true;
        } else {
            return false;
        }
    }
}