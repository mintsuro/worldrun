<?php
/* @var $this yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $race \cabinet\entities\cabinet\Race
 */

$diplomaLink = Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['/cabinet/pdf-generator/generate-diploma', 'raceId' => $race->id]);
?>

Здравствуйте, <?= $user->username ?>.

Забег завершен: <?= $race->name ?>

Ваше место в забеге: 1

<?= $diplomaLink ?>
