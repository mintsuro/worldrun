<?php
/* @var $this yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $order \cabinet\entities\shop\order\Order
 */

use yii\helpers\Html;

$orderLink = Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['/cabinet/order/view', 'id' => $order->id]);
?>
<div class="race-reg">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>.</p>

    <p>Ваш заказ отправлен. Трек-номер для отслеживания заказа: <?= $order->track_post ?> </p>

    <p><?= Html::a(Html::encode('Ссылка на заказ'), $orderLink) ?></p>
</div>
