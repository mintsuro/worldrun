<?php

use cabinet\helpers\DiscountHelper;
use cabinet\entities\shop\Discount;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $discount \cabinet\entities\shop\Discount */

$this->title = $discount->name;
$this->params['breadcrumbs'][] = ['label' => 'Скидки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?php if ($discount->isActive()): ?>
            <?= Html::a('Поместить в черновик', ['draft', 'id' => $discount->id], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
        <?php else: ?>
            <?= Html::a('Активный', ['activate', 'id' => $discount->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
        <?php endif; ?>
        <?= Html::a('Редактировать', ['update', 'id' => $discount->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $discount->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить скидку?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $discount,
                'attributes' => [
                    'name',
                    'value',
                    'size_products',
                    [
                        'attribute' => 'active',
                        'value' => function(Discount $model){
                            return DiscountHelper::statusLabel($model->active);
                        },
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
