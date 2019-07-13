<?php

namespace cabinet\forms\cabinet;

use yii\base\Model;
use yii\web\UploadedFile;
//use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Gd\Imagine;
use cabinet\entities\cabinet\Track;

class DownloadScreenForm extends Model
{
    public $distance;
    public $elapsed_time;
    public $file_screen;
    public $date_start;

    public function rules(){
        return [
            [['distance', 'elapsed_time', 'date_start', 'file_screen'], 'required'],
            [['distance'], 'integer'],
            [['elapsed_time'], 'time', 'format' => 'php:H:i:s'],
            [['date_start'], 'date', 'format' => 'php:d.m.Y'],
            ['file_screen', 'file', 'extensions' => 'jpg, jpeg, png'],
        ];
    }

    public function attributeLabels(){
        return [
            'distance' => 'Дистанция (в метрах)',
            'elapsed_time' => 'Общее время пробежки',
            'file_screen' => 'Загрузить скришнот',
            'date_start' => 'Дата старта пробежки'
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

    public function upload(Track $track)
    {
        $width = 300;
        $height = 300;
        $originPath = \Yii::getAlias('@uploadsRoot') . '/origin/screen/' .
            "{$track->id}-{$this->file_screen->baseName}" . '.' . $this->file_screen->extension;
        $thumbPath = \Yii::getAlias('@uploadsRoot') . '/thumb/screen/' .
            "$width-$height-{$track->id}-{$this->file_screen->baseName}" . '.' . $this->file_screen->extension;
        $size = new Box($width, $height);
        $imagine = new Imagine();

        if ($this->validate()) {
            $this->file_screen->saveAs($originPath);
            $imagine->open($originPath)->thumbnail($size)->save($thumbPath);
            return true;
        } else {
            return false;
        }
    }
}