<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */

?>

<div class="row">
    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{pager}",
        'itemView' => '_race',
    ]) ?>
</div>