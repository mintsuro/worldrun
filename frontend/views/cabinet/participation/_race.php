<?php

/* @var $this yii\web\View */
/* @var $model \cabinet\entities\cabinet\Race */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;

$url = Url::to(['race', 'id' => $model->id]);
?>

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
            <h4>Период проведения:</h4>
            <span><strong><?= date('d.m.Y', strtotime($model->date_start)) ?></strong></span> -
                <span><strong><?= date('d.m.Y', strtotime($model->date_end)) ?></strong></span>
        </div>
        <div class="info-text">
            <span><?= \cabinet\helpers\RaceHelper::statusLabel($model->status) ?></span>
        </div>
        <div class="info-text">
            <h4>Участники:</h4>
            <?php if($model->users) : ?>
                <span>
                    <a href="<?= Url::to(['/cabinet/participation/users', 'raceId' => $model->id]) ?>"><?= count($model->users) ?></a>
                </span>
            <?php else : ?>
                <span>0</span>
            <?php endif; ?>
        </div>
            <?php if(($model->user['id'] !== Yii::$app->user->identity->getId()) && (strtotime($model->date_reg_to) > time()) && (strtotime($model->date_reg_from) < time()) ): ?>
            <div class="info-text">
                <?= Html::a('Участвовать', Url::to(['/shop/checkout', 'raceId' => $model->id]), ['class' => 'btn btn-success']) ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="desc-race">
        <?= Html::encode(StringHelper::truncateWords(strip_tags($model->description), 100)) ?>
    </div>
</div>


