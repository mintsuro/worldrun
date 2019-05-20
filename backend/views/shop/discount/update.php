<?php

/* @var $this yii\web\View */
/* @var $discount \cabinet\entities\shop\Discount */
/* @var $model \cabinet\forms\manage\shop\product\DiscountForm */

$this->title = 'Редактировать скидку: ' . $discount->name;
$this->params['breadcrumbs'][] = ['label' => 'Скидки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $discount->name, 'url' => ['view', 'id' => $discount->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="page-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
