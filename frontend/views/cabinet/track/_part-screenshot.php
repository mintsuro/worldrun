<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\file\FileInput;

/**
 * @var $this \yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $urlOAuth \Strava\API\OAuth
 * @var $screenForm \cabinet\forms\cabinet\DownloadScreenForm
 */
?>

<div style="margin-bottom: 20px; padding-top: 30px" class="">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
        <?= $form->field($screenForm, 'file')->widget(FileInput::class, [
            'options' => [
                'accept' => 'image/*',
                'multiple' => false,
            ]
        ]) ?>
        <?= $form->field($screenForm, 'distance')->textInput() ?>
        <?= $form->field($screenForm, 'elapsed_time')->textInput() ?>
        <?= $form->field($screenForm, 'date_start')->widget(DatePicker::class, [
            'options' => ['placeholder' => 'Выберите дату начала забега'],
            'pluginOptions' => [
                'format' => 'dd.mm.yyyy',
                'todayHighlight' => true
            ]
        ]) ?>
        <?= Html::submitButton('Отправить на проверку', ['class' => 'btn btn-success']) ?>
    <?php $form::end() ?>
</div>