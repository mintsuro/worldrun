<?php

namespace cabinet\forms\manage\cabinet;

use yii\base\Model;

class TemplateForm extends Model
{
    public $diploma;
    public $start_number;
    public $top_diploma;
    public $top_start_number;

    public function rules()
    {
        return [
            [['diploma', 'start_number', 'top_diploma', 'top_start_number'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'diploma' => 'Диплом',
            'start_number' => 'Стартовый номер',
            'top_diploma' => 'Диплом победителя',
            'top_start_number' => 'Стартовый номер победителя',
        ];
    }
}