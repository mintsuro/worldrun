<?php

use kartik\date\DatePicker;
use cabinet\entities\cabinet\Race;
use cabinet\helpers\RaceHelper;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\cabinet\RaceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Забеги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('Создать забег', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'date_start',
                        'format' => 'date',
                    ],
                    [
                        'attribute' => 'date_end',
                        'format' => 'date',
                    ],
                    [
                        'attribute' => 'name',
                        'value' => function (Race $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'photo',
                        'value' => function(Race $model){
                            return $model->photo ? Html::img(\Yii::$app->get('frontendUrlManager')->baseUrl . '/uploads/origin/race/' . "$model->id-$model->photo",
                                ['style' => ['width' => '100px', 'height' => '70px']]) : null;
                        },
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'width: 100px'],
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => RaceHelper::statusList(),
                        'value' => function (Race $model) {
                            return RaceHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
