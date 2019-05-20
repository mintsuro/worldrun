<?php

/* @var $this yii\web\View */
/* @var $model \cabinet\forms\manage\shop\product\DiscountForm */

$this->title = 'Создать скидку';
$this->params['breadcrumbs'][] = ['label' => 'Скидки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
