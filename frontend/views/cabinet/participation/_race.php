<?php

/* @var $this yii\web\View */
/* @var $model \cabinet\entities\cabinet\Race */

use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to(['race', 'id' =>$model->id]);
?>

<div class="blog-posts-item">
    <?php if ($model->photo): ?>
        <div>
            <a href="<?= Html::encode($url) ?>">
                <img src="" alt="" class="img-responsive" />
            </a>
        </div>
    <?php endif; ?>
    <div class="h2"><a href="<?= Html::encode($url) ?>"><?= Html::encode($model->name) ?></a></div>
</div>


