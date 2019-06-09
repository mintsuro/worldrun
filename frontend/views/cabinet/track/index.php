<?php
/**
 * @var $this \yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $race \cabinet\entities\cabinet\Race
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
                        'attribute' => 'elapsed_time',
                        'value' => function(Track $model){
                            return date('H:i:s', $model->elapsed_time);
                        },
                    ],
                    [
                        'attribute' => 'pace',
                        'value' => function(Track $model){
                            if($model->download_method == $model::STRAVA_DOWNLOAD){
                                $resPace = 1000 / $model->pace;
                                $res = $resPace / 60;
                                $strDec = substr($res, 2, 1); //strlen(substr(strrchr($res, "."), 1));
                                $seconds = $strDec / 10 * 60;
                                $minute = floor($res);
                                return $minute . ':' . $seconds;
                            }else{
                                return null;
                            }
                        },
                    ],
                ],
            ]) ?>
            <?= Tabs::widget([
                'items' => [
                    [
                        'label' => 'Подключение через Strava',
                        'content' => $this->render('_part-strava', [
                            'user' => $user, 'urlOAuth' => $urlOAuth, 'race' => $race,
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
