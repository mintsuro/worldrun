<?php

/* @var $this yii\web\View */
/* @var $model \cabinet\entities\cabinet\Race */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;
use cabinet\helpers\RaceHelper;

$url = Url::to(['race', 'id' => $model->id]);
?>

<div class="race-item">
    <h4 class="tit"><?= Html::a(Html::encode($model->name), Url::to(['/cabinet/participation/view', 'id' => $model->id])) ?></h4>
    <div class="thumbnail">
        <?php if ($model->photo):
            echo Html::img($model->getThumbFileUrl('photo', 'thumb'), ['class' => 'img-responsive']);
        endif; ?>
    </div>
    <div class="info-race">
        <div class="info-text">
            <h4>Период регистрации:</h4>
            <span><strong><?= date('d.m.Y', strtotime($model->date_reg_from)) ?></strong></span> -
            <span><strong><?= date('d.m.Y', strtotime($model->date_reg_to)) ?></strong></span>
        </div>
        <div class="info-text">
            <h4>Период проведения:</h4>
            <span><strong><?= date('d.m.Y', strtotime($model->date_start)) ?></strong></span> -
                <span><strong><?= date('d.m.Y', strtotime($model->date_end)) ?></strong></span>
        </div>
        <div class="info-text">
            <?= RaceHelper::statusSpecLabel($model->status,  $model->date_reg_from, $model->date_reg_to) ?>
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
        <div class="desc-race">
            <?= Html::encode(StringHelper::truncateWords(strip_tags($model->description), 100)) ?>
        </div>
        <div class="info-text">
            <?= RaceHelper::buttonLabel($model); ?>

        </div>
    </div>
</div>


