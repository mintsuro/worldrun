<?php

/**
 * @var $this \yii\web\View
 * @var $model \cabinet\forms\user\ProfileEditForm
 * @var $profile \cabinet\entities\user\Profile
 */

//use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use cabinet\helpers\ProfileHelper;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use cabinet\access\Rbac;

$this->title = 'Редактирование профиля';
//$this->params['breadcrumbs'][] = ['label' => 'Кабинет', 'url' => ['cabinet/default/index']];
$this->params['breadcrumbs'][] =  $this->title;
?>

<div class="user-update">
    <div class="row">
        <div class="col-sm-9">
            <?php if(!Yii::$app->user->can(Rbac::ROLE_PARTICIPANT)): ?>
                <p style="padding: 10px" class="bg-danger"><b>Сначала заполните профиль.</b></p>
            <?php endif; ?>
            <h3 style="margin-top: 0px"><?= Html::encode($this->title) ?></h3>
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'first_name')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'last_name')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'sex')->dropDownList(ProfileHelper::sexList()) ?>
            <?= $form->field($model, 'age')->textInput() ?>
            <?= $form->field($model, 'city')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'phone')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'postal_code')->textInput() ?>
            <?= $form->field($model, 'address_delivery')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'size_costume')->dropDownList(ProfileHelper::sizeCostumeList()) ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>