<?php
/* @var $this yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $order \cabinet\entities\shop\order\Order
 */

$orderLink = Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['/cabinet/order/view', 'id' => $order->id]);
?>

Здравствуйте, <?= $user->username ?>.

Ваш заказ отправлен. Трек-номер для отслеживания заказа: <?= $order->track_post ?>

<?= $orderLink ?>
