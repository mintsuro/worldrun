<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \cabinet\entities\user\User */
/* @var $password string generate password */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/auth/reset/confirm', 'token' => $user->password_reset_token, 'password' => $password]);
?>
<div class="password-reset">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>,</p>

    <p>Ваш новый пароль: <strong><?= Html::encode($password) ?></strong></p>

    <p>Перейдите по ссылке для подтверждения своего нового пароля:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
