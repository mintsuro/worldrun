<?php
/* @var $this yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $race \cabinet\entities\cabinet\Race
 */

use yii\helpers\Html;

$generatePDF = Yii::$app->urlManager->createAbsoluteUrl(['/cabinet/pdf-generator/generate-start-number', 'raceId' => $race->id]);
?>
<div class="race-reg">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>.</p>

    <p>Вы зарегистрировались на забег: <strong><?= Html::encode($race->name) ?></strong></p>

    <p>Инструкция как участововать...</p>

    <p>Где загружать треки...</p>

    <p>Ваш стартовый номер:</p>

    <p><?= Html::a(Html::encode('Ссылка на стартовый номер в PDF'), $generatePDF) ?></p>
</div>
