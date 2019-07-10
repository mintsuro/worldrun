<?php
/* @var $this yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $track \cabinet\entities\cabinet\Track
 */

use yii\helpers\Html;

?>
<div class="race-reg">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>.</p>

    <p>Ваш скриншот обработан и <?php $track->isActive() ? print('принят') : print('отклонен') ?>.</p>

    <p>Причина отклонения: <?= $track->cancel_reason ?></p> <!-- преобразовать в текст -->
</div>
