<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use cabinet\access\Rbac;
use yii\widgets\Menu;

?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

<div class="row">
    <aside id="column-right" class="col-sm-3 hidden-xs">
        <?php $menuItems[] = ['label' => 'Профиль', 'url' => ['/cabinet/profile/edit']];
        if(Yii::$app->user->can(Rbac::ROLE_PARTICIPANT)){
            $menuItems[] = ['label' => 'Мои участия', 'url' => ['/cabinet/participation/index']];
            $menuItems[] = ['label' => 'Мои треки', 'url' => ['/cabinet/track/all']];
            $menuItems[] = ['label' => 'Мои заказы', 'url' => ['/cabinet/order/index']];
        } ?>
        <?= Menu::widget([
            'options' => ['class' => 'list-group'],
            'itemOptions' => ['class' => 'list-group-item'],
            'items' => $menuItems,
        ]); ?>
    </aside>

    <div id="content" class="col-sm-9">
        <?= $content ?>
    </div>
</div>

<?php $this->endContent() ?>
