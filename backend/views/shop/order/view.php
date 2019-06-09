<?php

use cabinet\entities\shop\order\Order;
use cabinet\helpers\OrderHelper;
use cabinet\helpers\PriceHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $order cabinet\entities\Shop\Order\Order */

$this->title = 'Заказ ' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $order->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $order->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить этот заказ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <h5 class="box-header with-border">Общие</h5>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $order,
                'attributes' => [
                    'id',
                    'created_at:datetime',
                    [
                        'attribute' => 'current_status',
                        'value' => OrderHelper::statusLabel($order->current_status),
                        'format' => 'raw',
                    ],
                    'user_id',
                    'customer_name',
                    'customer_phone',
                    [
                        'label' => 'Город покупателя',
                        'value' => function(Order $model){
                            return $model->user->profile->city;
                        },
                    ],
                    'delivery_index',
                    'delivery_address',
                    'cost',
                    'weight',
                    'track_post',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <h5 class="box-header with-border">Данные о забеге</h5>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $order,
                'attributes' => [
                    [
                        'label' => 'Забег',
                        'value' => function(Order $model){
                            return $model->race->name;
                        }
                    ],
                    [
                        'label' => 'Дата завершения забега',
                        'value' => function(Order $model){
                            return $model->race->date_end;
                        }
                    ]
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <h5 class="box-header with-border">Подарки</h5>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered" style="margin-bottom: 0">
                    <thead>
                    <tr>
                        <th class="text-left">Название товара</th>
                        <th class="text-right">Цена за товар</th>
                        <th class="text-right">Итоговая стоимость</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($order->items as $item): ?>
                        <tr>
                            <td class="text-left">
                                <?= Html::encode($item->product_name) ?>
                            </td>
                            <td class="text-right"><?= PriceHelper::format($item->price) ?></td>
                            <td class="text-right"><?= PriceHelper::format($item->getCost()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
