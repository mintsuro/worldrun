<?php

namespace cabinet\forms\manage\cabinet;

use cabinet\entities\cabinet\Race;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class RaceForm extends Model
{
    public $name;
    public $photo;
    public $status;
    public $date_start;
    public $date_end;

    private $_race;

    public function __construct(Race $race = null, $config = [])
    {
        if($race){
            $this->name = $race->name;
            $this->photo = $race->photo;
            $this->status = $race->status;
            $this->date_start = $race->date_start;
            $this->date_end = $race->date_end;
            $this->_race = $race;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'status', 'date_start', 'date_end'], 'required'],
            [['date_start', 'date_end'], 'date', 'format' => 'php:d.m.Y'],
            [['name'], 'string'],
            ['status', 'integer'],
            [['photo'], 'image'],
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
        ];
    }
}