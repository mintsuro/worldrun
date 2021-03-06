<?php

use kartik\date\DatePicker;
use cabinet\entities\cabinet\Track;
use cabinet\helpers\TrackHelper;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\cabinet\TrackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Треки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label' => 'Название забега',
                        'value' => function(Track $model){
                            return $model->race->name;
                        },
                        'contentOptions' => ['style'=>'white-space: normal;width: 200px']
                    ],
                    [
                        'label' => 'Автор забега',
                        'value' => function(Track $model){
                            return $model->user->username;
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'date_start',
                        'value' => function(Track $model){
                            return date('d.m.Y H:i:s', strtotime($model->date_start));
                        },
                    ],
                    [
                         'attribute' => 'created_at',
                         'format' => 'dateTime',
                    ],
                    [
                        'attribute' => 'distance',
                        'label' => 'Дистанция в метрах',
                    ],
                    [
                        'attribute' => 'elapsed_time',
                        'value' => function(Track $model){
                            return date('H:i:s', $model->elapsed_time);
                        },
                    ],
                    [
                        'attribute' => 'download_method',
                        'filter' => TrackHelper::statusList(),
                        'value' => function (Track $model) {
                            return TrackHelper::downloadLabel($model->download_method);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => TrackHelper::statusList(),
                        'value' => function (Track $model) {
                            return TrackHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'template' => '{view}{update}{delete}',
                        'class' => ActionColumn::class,
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
