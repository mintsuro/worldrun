<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use cabinet\entities\cabinet\Track;
use cabinet\helpers\TrackHelper;

/* @var $this yii\web\View */
/* @var $track \cabinet\entities\cabinet\Track */

$this->title = 'Трек: ' . $track->user->username;
$this->params['breadcrumbs'][] = ['label' => 'Треки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="track-view">
    <p>
        <?php if ($track->isActive()): ?>
            <?= Html::a('Поместить в модерацию', ['draft', 'id' => $track->id], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
        <?php else: ?>
            <?= Html::a('Активировать', ['activate', 'id' => $track->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
        <?php endif; ?>
        <?= Html::a('Редактировать', ['update', 'id' => $track->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $track->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить запись трека?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-header with-border">Основное</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $track,
                'attributes' => [
                    [
                        'label' => 'Автор забега',
                        'value' => function(Track $model){
                            return $model->user->username;
                        },
                        'format' => 'raw',
                    ],
                    [
                        'label' => 'Название забега',
                        'value' => function(Track $model){
                            return $model->race->name;
                        }
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
                        'attribute' => 'cancel_reason',
                        'value' => function(Track $model){
                            return \yii\helpers\ArrayHelper::getValue(TrackHelper::cancelList(), $model->cancel_reason);
                        },
                    ],
                    'cancel_text',
                ],
            ]) ?>
        </div>
    </div>

    <?php if(!empty($track->file_screen)): ?>
        <?php $fileUrl = \Yii::$app->get('frontendUrlManager')->baseUrl . '/uploads/origin/screen/' . $track->file_screen; ?>
        <div class="box" id="photos">
            <div class="box-header with-border">Фотографии</div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <?= Html::a(Html::img($fileUrl,
                            ['class' => 'img-responsive']),
                            $fileUrl,
                        ['class' => 'thumbnail', 'target' => '_blank']); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
