<?php
/**
 * @var $this \yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $screenForm \cabinet\forms\cabinet\DownloadScreenForm
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $urlOAuth \Strava\API\OAuth
 */

use cabinet\entities\cabinet\Track;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Tabs;

$this->title= 'Мои треки';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-update">
    <div class="row">
        <div class="col-sm-12">
            <h3 style="margin-top: 0"><?= Html::encode($this->title) ?></h3>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => false,
                'layout' => "{items}\n{pager}",
                'tableOptions' => ['class' => 'table table-striped table-bordered table-participant'],
                'columns' => [
                    //'created_at',
                    [
                        'attribute' => 'created_at',
                        'value' => function(Track $model){
                            return Yii::$app->formatter->asDatetime($model->created_at);
                        },
                    ],
                    [
                        'attribute' => 'date_start',
                        'value' => function(Track $model){
                            return date('m.d.Y H:i:s', strtotime($model->date_start));
                        },
                    ],
                    [
                        'attribute' => 'distance',
                        'value' => function(Track $model){
                            return $model->distance . ' м.';
                        }
                    ],
                    [
                        'attribute' => 'pace',
                        'value' => function(Track $model){
                            $pace = round($model->pace, 2);
                            $pace = substr_replace($pace, ':', 1, 1);
                            return $pace . ' мин. за км.';
                        },
                    ],
                ],
            ]) ?>
            <?= Tabs::widget([
                'items' => [
                    [
                        'label' => 'Подключение через Strava',
                        'content' => $this->render('_part-strava', [
                            'user' => $user, 'urlOAuth' => $urlOAuth
                        ]),
                        'active' => true,
                    ],
                    [
                        'label' => 'Загрузка скриншота',
                        'content' => $this->render('_part-screenshot', [
                            'screenForm' => $screenForm,
                        ]),
                    ],
                ],
            ])  ?>
        </div>
    </div>
</div>
