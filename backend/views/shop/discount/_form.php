<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use cabinet\helpers\DiscountHelper;

/* @var $this yii\web\View */
/* @var $model \cabinet\forms\manage\shop\product\DiscountForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Основные</div>
        <div class="box-body">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Дополнительные</div>
        <div class="box-body">
            <?= $form->field($model, 'fromDate')->widget(DatePicker::class, [
                'value' => date('Y-m-d'),
                'options' => ['placeholder' => 'Выберите дату начала действия скидка'],
                'pluginOptions' => [
                    'format' => 'dd.mm.yyyy',
                    'todayHighlight' => true,
                ]
            ]) ?>
            <?= $form->field($model, 'toDate')->widget(DatePicker::class, [
                'value' => date('Y-m-d'),
                'options' => ['placeholder' => 'Выберите дату конца действия скидки'],
                'pluginOptions' => [
                    'format' => 'dd.mm.yyyy',
                    'todayHighlight' => true,
                ]
            ]) ?>
            <?= $form->field($model, 'type')->dropDownList(DiscountHelper::typeList()) ?>
            <?= $form->field($model, 'typeValue')->dropDownList(DiscountHelper::typeValueList()) ?>
            <?= $form->field($model, 'sizeProducts')->textInput() ?>
            <?= $form->field($model, 'code')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
