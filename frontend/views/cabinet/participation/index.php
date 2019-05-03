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

$this->title= 'Мои участия';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-update">
    <div class="row">
        <div class="col-sm-12">
            <h3><?= Html::encode($this->title) ?></h3>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}\n{pager}",
                'columns' => [
                    [
                        'attribute' => 'id',
                        'label' => '№',
                    ],
                    'name',
                    [
                        'label' => 'Дата проведения',
                        'value' => function(Race $model){
                            return 'Период с ' . date('d.m.Y', $model->date_start) . ' по ' . date('d.m.Y', $model->date_end);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function (Race $model){
                            return RaceHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function(){
                            return Html::a('Мои треки', Url::to(['#']));
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function(){
                            return Html::a('Ссылка на стартовый номер', Url::to(['#']));
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function(){
                            return Html::a('Подарки', Url::to(['#']));
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function(){
                            return Html::a('Диплом', Url::to(['#']));
                        },
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
        </div>
    </div>

</div>
