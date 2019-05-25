<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */

?>

<div>
    <?= \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{pager}",
        'itemView' => '_race',
        'itemOptions' => ['class' => 'col-sm-12 index-race'],
        'options' => ['class' => 'row'],
    ]) ?>
</div>