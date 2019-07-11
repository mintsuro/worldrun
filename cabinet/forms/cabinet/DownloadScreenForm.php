<?php

namespace cabinet\forms\cabinet;

use yii\base\Model;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;

class DownloadScreenForm extends Model
{
    public $distance;
    public $elapsed_time;
    public $file;
    public $date_start;

    public function rules(){
        return [
            [['distance', 'elapsed_time', 'date_start', 'file'], 'required'],
            [['distance'], 'integer'],
            [['elapsed_time'], 'time', 'format' => 'php:H:i:s'],
            //['elapsed_time', 'default', 'value' => '00:00:00'],
            [['date_start'], 'date', 'format' => 'php:d.m.Y'],
            [['file'], 'file', 'extensions' => 'jpg, jpeg, png'],
        ];
    }

    public function attributeLabels(){
        return [
            'distance' => 'Дистанция (в метрах)',
            'elapsed_time' => 'Общее время пробежки',
            'file' => 'Загрузить скришнот',
            'date_start' => 'Дата старта пробежки'
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->file = UploadedFile::getInstance($this, 'file');
            return true;
        }
        return false;
    }

    public function upload()
    {
        $width = 1024;
        $height = 768;
        $originPath = \Yii::getAlias('@uploadsRoot') . '/origin/screen/' . $this->file->baseName . '.' . $this->file->extension;
        $thumbPath = \Yii::getAlias('@uploadsRoot') . '/thumb/screen/' . $this->file->baseName . "x$width-$height" . '.' . $this->file->extension;
        $size = new Box($width, $height);

        if ($this->validate()) {
            $this->file->saveAs($originPath);
            Image::thumbnail($originPath, $width, $height)->save($thumbPath, ['quality' => 100]);
            return true;
        } else {
            return false;
        }
    }
}