<?php

/**
 * @var $this \yii\web\View
 * @var $model \cabinet\forms\user\ProfileEditForm
 * @var $profile \cabinet\entities\user\Profile
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use cabinet\helpers\ProfileHelper;

$this->title = 'Редактирование профиля';
//$this->params['breadcrumbs'][] = ['label' => 'Cabinet', 'url' => ['cabinet/default/index']];
$this->params['breadcrumbs'][] = 'Профиль';
?>
<div class="user-update">

    <div class="row">
        <div class="col-sm-6">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'first_name')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'last_name')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'sex')->dropDownList(ProfileHelper::sexList()) ?>
            <?= $form->field($model, 'age')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'city')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'postal_code')->textInput() ?>
            <?= $form->field($model, 'address_delivery')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model, 'size_costume')->textInput(['maxLength' => true]) ?>


            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>