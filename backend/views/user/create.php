<?php

/* @var $this yii\web\View */
/* @var $model \cabinet\forms\manage\user\UserCreateForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Создать пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'password')->passwordInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'role')->dropDownList($model->rolesList()) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
