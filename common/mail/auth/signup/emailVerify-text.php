<?php

/* @var $this yii\web\View */
/* @var $user cabinet\entities\user\User */
/* @var $password string */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/signup/confirm', 'token' => $user->verification_token]);
?>

Здравствуйте, <?= $user->username ?>,

Ваш личный сгенерированный пароль для входа на сайт: <?= $password ?>

Перейдите по ссылке для подтверждения вашей электронной почты:

<?= $verifyLink ?>
