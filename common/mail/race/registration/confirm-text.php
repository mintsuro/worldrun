<?php

/* @var $this yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $race \cabinet\entities\cabinet\Race
 */

$generatePDF = Yii::$app->urlManager->createAbsoluteUrl(['/cabinet/pdf-generator/generate-start-number', 'raceId' => $race->id]);
?>
Здравствуйте, <?= $user->username ?>.

Вы зарегистрировались на забег: <?= $race->name ?>

Инструкция как участововать...

Где загружать треки...

Ваш стартовый номер:

Перейдите по ссылке для подтверждения своего пароля:

<?= $generatePDF ?>
