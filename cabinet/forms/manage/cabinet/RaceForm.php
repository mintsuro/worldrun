<?php

namespace cabinet\forms\manage\cabinet;

use cabinet\entities\cabinet\Race;
use cabinet\forms\CompositeForm;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * @property TemplateForm $template
 */
class RaceForm extends CompositeForm
{
    public $name;
    public $description;
    public $photo;
    public $status;
    public $temp_start_number;
    public $temp_diploma;
    public $date_start;
    public $date_end;
    public $date_reg_from;
    public $date_reg_to;
    public $type;

    private $_race;

    public function __construct(Race $race = null, $config = [])
    {
        if($race){
            $this->name = $race->name;
            $this->status = $race->status;
            $this->date_start = date('d.m.Y', strtotime($race->date_start));
            $this->date_end = date('d.m.Y', strtotime($race->date_end));
            $this->date_reg_from = date('d.m.Y', strtotime($race->date_reg_from));
            $this->date_reg_to = date('d.m.Y', strtotime($race->date_reg_to));
            $this->type = $race->type;
            $this->template = new TemplateForm();
            $this->_race = $race;
        }else{
            $this->template = new TemplateForm($race);
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'status', 'date_start', 'date_end', 'date_reg_from', 'date_reg_to'], 'required'],
            [['date_start', 'date_end', 'date_reg_from', 'date_reg_to'], 'date', 'format' => 'php:d.m.Y'],
            [['name', 'description'], 'string'],
            [['status', 'type'], 'integer'],
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

    protected function internalForms(): array
    {
        return ['template'];
    }
}