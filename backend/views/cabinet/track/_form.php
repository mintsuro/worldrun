<?php

/* @var $this yii\web\View */
/* @var $model \cabinet\forms\manage\cabinet\TrackForm */

use cabinet\helpers\TrackHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="user-update">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'distance')->textInput() ?>
    <?= $form->field($model, 'elapsed_time')->textInput(['value' => date('H:i:s', $model->elapsed_time)]) ?>
    <?= $form->field($model, 'status')->dropDownList(TrackHelper::statusList()) ?>

    <?= $form->field($model, 'cancel_reason')->dropDownList(TrackHelper::cancelList()) ?>
    <?= $form->field($model, 'cancel_text')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
