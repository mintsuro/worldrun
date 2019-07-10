<?php
/* @var $this yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $track \cabinet\entities\cabinet\Track
 */

use yii\helpers\Html;

?>

Здравствуйте, <?= Html::encode($user->username) ?>.

Ваш скриншот обработан и <?php $track->isActive() ? print('принят') : print('отклонен') ?>.

Причина отклонения: <?= $track->cancel_reason ?>
