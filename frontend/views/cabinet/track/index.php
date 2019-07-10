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
use cabinet\helpers\TrackHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use cabinet\entities\cabinet\Race;

$this->title= 'Треки';
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
                            $date = ($model->download_method == Track::STRAVA_DOWNLOAD) ? date('d.m.Y H:i:s', strtotime($model->date_start)) :
                                date('d.m.Y', strtotime($model->date_start));
                            return $date;
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
                                return TrackHelper::getPace($model->pace);
                            }else{
                                return null;
                            }
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function(Track $model){
                            if($model->status !== Track::STATUS_CANCEL){
                                return TrackHelper::statusLabel($model->status);
                            }else{
                                return TrackHelper::statusLabel($model->status) . '<span class="mgr">' .
                                    ArrayHelper::getValue(TrackHelper::cancelList(), $model->cancel_reason) . '</span>' .
                                    "<p class='mgr-p'>$model->cancel_text</p>";
                            }
                        },
                        'format' => 'raw',
                    ]
                ],
            ]); ?>
            <?php if($race->status !== Race::STATUS_COMPLETE && strtotime($race->date_end) > time() &&
                        $race->getCountSimpleTrack(Yii::$app->user->getId())):
                echo Tabs::widget([
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
                ]);
            endif; ?>
        </div>
    </div>
</div>
