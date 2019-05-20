<?php

use cabinet\entities\shop\order\Order;
use cabinet\helpers\OrderHelper;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\shop\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('Экспорт', ['export'], ['class' => 'btn btn-primary', 'data-method' => 'post', 'data-confirm' => 'Экспорт?']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'value' => function (Order $model) {
                            return Html::a(Html::encode($model->id), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Дата создания',
                        'format' => 'datetime',
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Статус',
                        'filter' => $searchModel->statusList(),
                        'value' => function (Order $model) {
                            return OrderHelper::statusLabel($model->current_status);
                        },
                        'format' => 'raw',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
