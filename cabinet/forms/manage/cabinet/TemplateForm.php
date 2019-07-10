<?php

namespace cabinet\forms\manage\cabinet;

use yii\base\Model;
use cabinet\entities\cabinet\Race;

class TemplateForm extends Model
{
    public $diploma;
    public $start_number;
    public $top_diploma;

    public function __construct(Race $race = null, array $config = [])
    {
        if($race){
            $this->diploma = $race->template->diploma;
            $this->top_diploma = $race->template->diploma_top;
            $this->start_number = $race->template->start_number;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['diploma', 'start_number', 'top_diploma'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'diploma' => 'Диплом',
            'start_number' => 'Стартовый номер',
            'top_diploma' => 'Диплом победителя',
        ];
    }
}