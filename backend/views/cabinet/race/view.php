<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use cabinet\entities\cabinet\Race;
use cabinet\helpers\RaceHelper;

/* @var $this yii\web\View */
/* @var $race \cabinet\entities\cabinet\Race */

$this->title = $race->name;
$this->params['breadcrumbs'][] = ['label' => 'Забеги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $race->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $race->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить запись забега?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-header with-border">Основное</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $race,
                'attributes' => [
                    'name',
                    'description',
                    [
                        'attribute' => 'status',
                        'value' => function(Race $race){
                            return RaceHelper::statusLabel($race->status);
                        },
                        'format' => 'raw',
                    ],
                    'date_start:date',
                    'date_end:date',
                    'date_reg_from:date',
                    'date_reg_to:date',
                ],
            ]) ?>
        </div>
    </div>

    <?php if($products = $race->products){ ?>
        <div class="box">
            <div class="box-header with-border">Товары</div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered" style="margin-bottom: 0">
                        <thead>
                        <tr>
                            <th width="150" class="text-left">Изображение</th>
                            <th class="text-left">Название</th>
                            <th class="text-left">Стоимость</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="text-left">
                                    <?= Html::img($product->getThumbFileUrl('photo', 'thumb'), ['class' => 'img-responsive']); ?>
                                </td>
                                <td class="text-left">
                                    <?= $product->name ?>
                                </td>
                                <td class="text-left">
                                    <?= "$product->price руб." ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
