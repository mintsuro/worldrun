<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \cabinet\forms\auth\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use rmrevin\yii\ulogin\ULogin;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <?php echo ULogin::widget([
            // widget look'n'feel
            'display' => ULogin::D_PANEL,

            // required fields
            'fields' => [ULogin::F_FIRST_NAME, ULogin::F_LAST_NAME, ULogin::F_EMAIL, ULogin::F_PHONE, ULogin::F_CITY, ULogin::F_COUNTRY, ULogin::F_PHOTO_BIG],

            // optional fields
            'optional' => [ULogin::F_BDATE],

            // login providers
            'providers' => [ULogin::P_VKONTAKTE, ULogin::P_FACEBOOK, ULogin::P_TWITTER, ULogin::P_GOOGLE],

            // login providers that are shown when user clicks on additonal providers button
            'hidden' => [],

            // where to should ULogin redirect users after successful login
            'redirectUri' => ['sign/ulogin'],

            // force use https in redirect uri
            'forceRedirectUrlScheme' => 'https',

            // optional params (can be ommited)
            // force widget language (autodetect by default)
            'language' => ULogin::L_RU,

            // providers sorting ('relevant' by default)
            'sortProviders' => ULogin::S_RELEVANT,

            // verify users' email (disabled by default)
            'verifyEmail' => '0',

            // mobile buttons style (enabled by default)
            'mobileButtons' => '1',
            ]); ?>
        </div>
    </div>
</div>
