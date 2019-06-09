<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use cabinet\entities\shop\order\Status;
use cabinet\entities\cabinet\Race;
use cabinet\helpers\RaceHelper;

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
                    'name',
                    [
                        'label' => 'Дата проведения',
                        'value' => function(Race $model){
                            return 'Период с ' . date('d.m.Y', strtotime($model->date_start)) . ' по ' . date('d.m.Y', strtotime($model->date_end));
                        },
                        'format' => 'raw',
                        'options' => ['width', '100px']
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function (Race $model){
                            return RaceHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function(Race $model){
                            return Html::a('Мои треки', Url::to(['/cabinet/track/index', 'raceId' => $model->id]));
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function(Race $model){
                            return Html::a('Ссылка на стартовый номер', Url::to(['/cabinet/pdf-generator/generate-start-number', 'raceId' => $model->id]));
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function(Race $model){
                            if(!empty($model->order)):
                                $linkPay = Html::a('Оплатить', Url::to(['/cabinet/order/race', 'raceId' => $model->id]), [
                                    'class' => 'label label-success pay-link']);
                                $tag = $model->order->current_status == Status::NEW ? $linkPay : null;
                                return Html::a('Подарки', Url::to(['/cabinet/order/race', 'raceId' => $model->id])) . "<br/>" . $tag;

                            else:
                                return Html::tag('span', 'Подарки');
                            endif;
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function(Race $model){
                            return Html::a('Диплом', Url::to(['/cabinet/pdf-generator/generate-diploma', 'raceId' => $model->id]));
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
