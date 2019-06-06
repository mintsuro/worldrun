<?php

namespace cabinet\forms\cabinet;

use yii\base\Model;
use yii\web\UploadedFile;
use yii\imagine\Image;

class DownloadScreenForm extends Model
{
    public $distance;
    public $elapsed_time;
    public $file;
    public $date_start;

    public function rules(){
        return [
            [['distance', 'elapsed_time', 'date_start', 'file'], 'required'],
            [['distance', 'elapsed_time'], 'integer'],
            [['date_start'], 'date', 'format' => 'php:m.d.Y'],
            [['file'], 'file', 'extensions' => 'jpg, jpeg, png'],
        ];
    }

    public function attributeLabels(){
        return [
            'distance' => 'Дистанция (в метрах)',
            'elapsed_time' => 'Общее время (в секундах)',
            'file' => 'Загрузить скришнот',
            'date_start' => 'Дата пробежки'
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

        if ($this->validate()) {
            $this->file->saveAs($originPath);
            Image::thumbnail($originPath, $width, $height)->save($thumbPath, ['quality' => 90]);
            return true;
        } else {
            return false;
        }
    }
}