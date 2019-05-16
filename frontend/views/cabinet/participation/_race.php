<?php

/* @var $this yii\web\View */
/* @var $model \cabinet\entities\cabinet\Race */

use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to(['race', 'id' => $model->id]);
?>

<div class="col-sm-6">
    <div class="race-item">
        <h4 class="tit"><?= Html::encode($model->name) ?></h4>
        <div class="thumbnail">
            <?php if ($model->photo): ?>
                <?= Html::img(\Yii::$app->get('frontendUrlManager')->baseUrl . '/uploads/origin/race/' . $model->photo,
                    ['style' => ['width' => '200px', 'height' => '200px'], 'class' => 'img-responsive']) ?>
            <?php endif; ?>
        </div>
        <div class="info-race">
            <div class="info-text">
                <h4>Дата проведения:</h4>
                <span><strong><?= date('d.m.Y', $model->date_start) ?></strong></span> -
                    <span><strong><?= date('d.m.Y', $model->date_end) ?></strong></span>
            </div>
            <div class="info-text">
                <h4>Статус:</h4>
                <span><?= \cabinet\helpers\RaceHelper::statusLabel($model->status) ?></span>
            </div>
            <div class="info-text">
                <h4>Количество участников:</h4>
                <span>1</span>
            </div>
            <div class="info-text">
                <?= Html::a('Участвовать', Url::to(['/shop/checkout', 'raceId' => $model->id]), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
</div>

