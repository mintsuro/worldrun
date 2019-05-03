<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user cabinet\entities\user\User */
/* @var $password string */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/signup/confirm', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>,</p>

    <p>Ваш личный сгенерированный пароль для входа на сайт: <strong><?= Html::encode($password) ?></strong></p>

    <p>Перейдите по ссылке для подтверждения вашей электронной почты:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
