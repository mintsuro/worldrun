<?php
/**
 * @var $this \yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $urlOAuth \Strava\API\OAuth
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

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
                    [
                        'attribute' => 'created_at',
                        'format' => 'date',
                    ],
                    'date_start',
                    'distance',
                    'pace',
                ],
            ]) ?>
            <?php  ?>
            <div style="margin-bottom: 20px" class="">
                <?php if(!$user->strava) : ?>
                    <?= Html::a(Html::encode('Подключить Strava'),
                            Url::to($urlOAuth),
                        ['class' => 'btn btn-success']
                    ); ?>
                <?php else : ?>
                    <?= Html::a(Html::encode('Загрузить трек'),
                        Url::to(['/cabinet/track/add', 'raceId' => \Yii::$app->request->getQueryParam('raceId')]),
                        ['class' => 'btn btn-success']
                    ); ?>
                    <?= Html::a(Html::encode('Сменить аккаунт Strava'),
                        Url::to($urlOAuth),
                        ['class' => 'btn btn-success']
                    ); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
