<?php
/* @var $this yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $race \cabinet\entities\cabinet\Race
 */

$raceLink = Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['/cabinet/participation/view', 'id' => $race->id]);
?>

Здравствуйте, <?= $user->username ?>.

Начался забег: <?= $race->name ?>

<?= $raceLink ?>
