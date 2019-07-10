<?php

use cabinet\entities\shop\product\Product;
use cabinet\helpers\PriceHelper;
use cabinet\helpers\ProductHelper;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\shop\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('Создать товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                /* 'rowOptions' => function (Product $model) {
                    return $model->quantity <= 0 ? ['style' => 'background: #fdc'] : [];
                }, */
                'columns' => [
                    [
                        'attribute' => 'id',
                        'filter' => false,
                    ],
                    [
                        'label' => 'Название',
                        'attribute' => 'name',
                        'value' => function (Product $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'label' => 'Стоимость',
                        'attribute' => 'price',
                        'value' => function (Product $model) {
                            return PriceHelper::format($model->price);
                        },
                    ],
                    [
                        'attribute' => 'sort',
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'race_id',
                        'value' => function(Product $model){
                            return $model->race->name;
                        }
                    ],
                    [
                        'value' => function (Product $model) {
                            return $model->photo ? Html::img(\Yii::$app->get('frontendUrlManager')->baseUrl . '/uploads/origin/product/' . $model->photo,
                                ['style' => ['width' => '100px', 'height' => '100px']]) : null;
                        },
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'width: 100px'],
                    ],
                    [
                        'label' => 'Статус',
                        'attribute' => 'status',
                        'filter' => $searchModel->statusList(),
                        'value' => function (Product $model) {
                            return ProductHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
