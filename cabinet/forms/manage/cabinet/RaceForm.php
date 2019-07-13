<?php

namespace cabinet\forms\manage\cabinet;

use cabinet\entities\cabinet\Race;
use cabinet\forms\CompositeForm;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

/**
 * @property TemplateForm $template
 */
class RaceForm extends CompositeForm
{
    public $name;
    public $description;
    public $status;
    public $strava_only;
    public $temp_start_number;
    public $temp_diploma;
    public $date_start;
    public $date_end;
    public $date_reg_from;
    public $date_reg_to;
    public $type;
    public $photo;

    private $_race;

    public function __construct(Race $race = null, $config = [])
    {
        if($race){
            $this->name = $race->name;
            $this->status = $race->status;
            $this->strava_only = $race->strava_only;
            $this->date_start = date('d.m.Y', strtotime($race->date_start));
            $this->date_end = date('d.m.Y', strtotime($race->date_end));
            $this->date_reg_from = date('d.m.Y', strtotime($race->date_reg_from));
            $this->date_reg_to = date('d.m.Y', strtotime($race->date_reg_to));
            $this->type = $race->type;
            $this->template = new TemplateForm($race);
            $this->_race = $race;
        }else{
            $this->template = new TemplateForm();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'status', 'date_start', 'date_end', 'date_reg_from', 'date_reg_to'], 'required'],
            [['date_start', 'date_end', 'date_reg_from', 'date_reg_to'], 'date', 'format' => 'php:d.m.Y'],
            [['name', 'description'], 'string'],
            [['status', 'type', 'strava_only'], 'integer'],
            [['photo'], 'file', 'extensions' => 'jpeg, png, jpg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название забега',
            'status' => 'Статус',
            'photo' => 'Фото',
            'strava_only' => 'Загрузка только для Strava',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата завершения',
            'date_reg_from' => 'Дата начала регистрации',
            'date_reg_to' => 'Дата окончания регистрации',
            'type' => 'Тип забега',
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

    protected function internalForms(): array
    {
        return ['template'];
    }
}