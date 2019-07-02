<?php
/* @var $this yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $order \cabinet\entities\shop\order\Order
 */

use yii\helpers\Html;

$payLink = Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['/cabinet/order/view', 'id' => $order->id]);
?>
Здравствуйте, <?= $user->username ?>

У вас имеется оформленный неоплаченный заказ:

<?= $payLink ?>

