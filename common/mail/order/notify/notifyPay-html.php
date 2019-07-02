<?php
/* @var $this yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $order \cabinet\entities\shop\order\Order
 */

use yii\helpers\Html;

$payLink = Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['/cabinet/order/view', 'id' => $order->id]);
?>
<div class="race-reg">
    <p>Здравствуйте, <?= Html::encode($user->username) ?>.</p>

    <p>У вас имеется оформленный неоплаченный заказ: </p>

    <p><?= Html::a(Html::encode('Оплата заказа'), $payLink) ?></p>
</div>
