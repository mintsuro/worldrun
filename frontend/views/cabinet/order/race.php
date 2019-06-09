<?php

use cabinet\helpers\OrderHelper;
use cabinet\helpers\PriceHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $order cabinet\entities\shop\order\Order */

$this->title = 'Заказ';
$this->params['breadcrumbs'][] = ['label' => 'Кабинет', 'url' => ['cabinet/participation/index']];
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h3 style="margin-top: 0px"><?= Html::encode($this->title) ?></h3>

    <?= DetailView::widget([
        'model' => $order,
        'attributes' => [
            'created_at:datetime',
            [
                'attribute' => 'current_status',
                'value' => OrderHelper::statusLabel($order->current_status),
                'format' => 'raw',
            ],
            'deliveryData.index',
            'deliveryData.address',
            'cost',
        ],
    ]) ?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-left">Название товара</th>
                <th class="text-right">Цена за товар</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($order->items as $item): ?>
                <tr>
                    <td class="text-left">
                        <?= Html::encode($item->product_name) ?>
                    </td>
                    <td class="text-right"><?= PriceHelper::format($item->price) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($order->canBePaid()): ?>
        <p>
            <?= Html::a('Оплатить', ['/payment/yandexkassa/invoice', 'id' => $order->id], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

</div>