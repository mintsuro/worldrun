<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use rmrevin\yii\ulogin\ULogin;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                    <br>
                    Need new verification email? <?= Html::a('Resend', ['site/resend-verification-email']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
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
            'redirectUri' => ['site/ulogin-auth'], //['sign/ulogin'],

            // force use https in redirect uri
            'forceRedirectUrlScheme' => 'http',

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
