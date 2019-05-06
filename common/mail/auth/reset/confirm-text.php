<?php

/* @var $this yii\web\View */
/* @var $user \cabinet\entities\user\User */
/* @var $password string generate password */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/auth/reset/confirm', 'token' => $user->password_reset_token, 'password' => $password]);
?>
Здравствуйте, <?= $user->username ?>,

Ваш новый пароль: <?= $password ?>

Перейдите по ссылке для подтверждения своего пароля:

<?= $resetLink ?>
