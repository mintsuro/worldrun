<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use cabinet\entities\cabinet\Race;
use cabinet\helpers\RaceHelper;
use cabinet\helpers\OrderHelper;

$this->title= 'Мои участия';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-update">
    <div class="row">
        <div class="col-sm-12">
            <h3 style="margin-top: 0"><?= Html::encode($this->title) ?></h3>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}\n{pager}",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-participant'],
                'columns' => [
                    [
                        'attribute' => 'id',
                        'label' => '№',
                    ],
                    [
                        'attribute' => 'name',
                        'value' => function(Race $model){
                            return Html::a($model->name, Url::to(['/cabinet/participation/view', 'id' => $model->id]));
                        },
                        'format' => 'raw'
                    ],
                    [
                        'label' => 'Дата проведения',
                        'value' => function(Race $model){
                            return 'С ' . date('d.m.Y', strtotime($model->date_start)) . ' по ' . date('d.m.Y', strtotime($model->date_end));
                        },
                        'format' => 'raw',
                        'options' => ['width', '100px']
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function (Race $model){
                            return RaceHelper::statusSpecLabel($model->status, $model->date_reg_from, $model->date_reg_to);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function(Race $model){
                            if(strtotime($model->date_start) < time()):
                                return Html::a('Треки', Url::to(['/cabinet/track/index', 'raceId' => $model->id]));
                            else:
                                return Html::tag('span', 'Треки');
                            endif;
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function(Race $model){
                            return Html::a('Стартовый номер', Url::to(['/cabinet/pdf-generator/generate-start-number', 'raceId' => $model->id]), [
                                'target' => '_blank',
                            ]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function(Race $model){
                            if(!empty($model->order)):
                                return OrderHelper::statusColumn($model);
                            else:
                                return Html::tag('span', 'Подарки');
                            endif;
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function(Race $model){
                            $tracks = $model->getTracks()->andWhere(['user_id' => Yii::$app->user->getId()])->count();
                            if(strtotime($model->date_end) < time() && $model->status !== Race::STATUS_WAIT) :
                                if($tracks > 0){
                                    return Html::a('Диплом', Url::to(['/cabinet/pdf-generator/generate-diploma', 'raceId' => $model->id]), [
                                        'target' => '_blank',
                                    ]);
                                }else{
                                    return Html::tag('span', 'Диплом');
                                }
                            else :
                                return Html::tag('span', 'Диплом') .
                                    Html::tag('span', 'Будет доступен после забега', ['class' => 'label alt label-default']);
                            endif;
                        },
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
            <div style="margin-bottom: 20px" class="">
                <?= Html::a(Html::encode('Новое участие'),
                    Url::to(['all']),
                    ['class' => 'btn btn-success']
                ); ?>
            </div>
        </div>
    </div>

</div>
