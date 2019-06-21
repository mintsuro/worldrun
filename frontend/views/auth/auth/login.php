<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \cabinet\forms\auth\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-7 col-lg-offset-2">
        <div class="well">
            <h2><?= $this->title ?></h2>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput(['placeholder' => 'Введите email'])->label('Email') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div>
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                <span>или <?= Html::a('Зарегистрируйтесь', Url::to(['/auth/signup/request'])) ?></span>
            </div>

            <div style="margin-top: 1em">
                <?= Html::a('Забыли пароль?', Url::to(['/auth/reset/request'])) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <?= \frontend\widgets\auth\UloginAuthWidget::widget(); ?>
        </div>
    </div>
</div>

