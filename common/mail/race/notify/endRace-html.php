<?php
/* @var $this yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $race \cabinet\entities\cabinet\Race
 */

use yii\helpers\Html;

$raceLink = Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['/cabinet/participation/view', 'id' => $race->id]);
?>
<div class="race-reg">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>.</p>

    <p>Забег будет завершен через 24 часа: <?= $race->name ?></p>

    <p><?= Html::a(Html::encode('Ссылка на забег'), $raceLink) ?></p>
</div>
