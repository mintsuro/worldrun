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
                         /*'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date_from',
                            'attribute2' => 'date_to',
                            'type' => DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd',
                            ],
                        ]), */
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
                    'photo',
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
