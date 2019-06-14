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
    public $elapsed_time;
    public $status;
    public $cancel_reason;
    public $cancel_text;

    private $_track;

    public function __construct(Track $track = null, $config = [])
    {
        if($track){
            $this->distance = $track->distance;
            $this->elapsed_time = $track->elapsed_time;
            $this->status = $track->status;
            $this->_track = $track;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['distance', 'elapsed_time', 'status'], 'required'],
            [['distance', 'status'], 'integer'],
            [['elapsed_time'], 'time', 'format' => 'php:H:i:s'],
            ['elapsed_time', 'default', 'value' => '00:00:00'],
            ['cancel_text', 'string'],
            ['cancel_reason', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'distance' => 'Дистанция',
            'elapsed_time' => 'Время пробежки',
            'status' => 'Статус',
            'cancel_reason' => 'Причина отклонения',
            'cancel_text' => 'Описание другой причины',
        ];
    }
}