<?php

use cabinet\entities\shop\Discount;
use cabinet\helpers\DiscountHelper;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Скидка от количества товаров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('Создать скидку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => false,
                'columns' => [
                    [
                        'attribute' => 'name',
                        'value' => function (Discount $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    'value',
                    [
                        'attribute' => 'type',
                        'value' => function(Discount $model){
                            return DiscountHelper::typeLabel($model->type);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'type_value',
                        'value' => function(Discount $model){
                            return DiscountHelper::typeValueLabel($model->type_value);
                        },
                        'format' => 'raw',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
