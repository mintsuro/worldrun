<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 * @var $user \cabinet\entities\user\User
 * @var $urlOAuth \Strava\API\OAuth
 */
?>

<div style="margin-bottom: 20px; padding-top: 30px" class="">
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