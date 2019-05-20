<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use cabinet\access\Rbac;

?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

<div class="row">
    <aside id="column-right" class="col-sm-3 hidden-xs">
        <div class="list-group">
            <a href="<?= Html::encode(Url::to(['/cabinet/profile/edit'])) ?>" class="list-group-item">Профиль</a>
            <?php if(Yii::$app->user->can(Rbac::ROLE_PARTICIPANT)){ ?>
                <a href="<?= Html::encode(Url::to(['/cabinet/participation/index', 'userId' => \Yii::$app->user->getId()])) ?>" class="list-group-item">Мои участия</a>
                <a href="<?= Html::encode(Url::to(['/cabinet/track/index'])) ?>" class="list-group-item">Мои треки</a>
                <a href="<?= Html::encode(Url::to(['/cabinet/order/index'])) ?>" class="list-group-item">Мои заказы</a>
            <?php } ?>
        </div>
    </aside>

    <div id="content" class="col-sm-9">
        <?= $content ?>
    </div>
</div>

<?php $this->endContent() ?>
