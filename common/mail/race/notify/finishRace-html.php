<?php
/* @var $this yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $race \cabinet\entities\cabinet\Race
 * @var $position integer
 */

use yii\helpers\Html;

$diplomaLink = Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['/cabinet/pdf-generator/generate-diploma', 'raceId' => $race->id]);
?>
<div class="race-reg">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>.</p>

    <p>Забег завершен: <?= $race->name ?></p>

    <p>Ваше место в забеге: <?= $position ?></p>

    <p><?= Html::a(Html::encode('Ссылка на диплом'), $diplomaLink) ?></p>
</div>
