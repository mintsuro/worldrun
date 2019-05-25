<?php

/* @var $this yii\web\View */
/* @var $model \cabinet\forms\manage\cabinet\RaceForm */
/* @var $race \cabinet\entities\cabinet\Race */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\DatePicker;
use kartik\file\FileInput;

?>
<div class="user-update">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList(\cabinet\helpers\RaceHelper::statusList()) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'date_start')->widget(DatePicker::class, [
        'value' => date('d.m.Y'),
        'options' => ['placeholder' => 'Выберите дату начала забега'],
        'pluginOptions' => [
            'format' => 'dd.mm.yyyy',
            'todayHighlight' => true
        ]
    ]) ?>
    <?= $form->field($model, 'date_end')->widget(DatePicker::class, [
        'value' => date('d.m.Y'),
        'options' => ['placeholder' => 'Выберите дату окончания забега'],
        'pluginOptions' => [
            'format' => 'dd.mm.yyyy',
            'todayHighlight' => true
        ]
    ]) ?>

    <div class="box box-default">
        <div class="box-header with-border">Фотография</div>
        <div class="box-body">
            <?php echo $form->field($model, 'photo')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => false,
                ]
            ])->label(false) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
