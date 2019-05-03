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
                    [
                        'attribute' => 'status',
                        'value' => function(Race $race){
                            return RaceHelper::statusLabel($race->status);
                        },
                        'format' => 'raw',
                    ],
                    'date_start:date',
                    'date_end:date'
                ],
            ]) ?>
        </div>
    </div>
</div>
