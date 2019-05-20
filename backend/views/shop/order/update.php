<?php

/* @var $this yii\web\View */
/* @var $order cabinet\entities\shop\order\Order */
/* @var $model cabinet\forms\manage\shop\order\OrderEditForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Редактировать заказ: ' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $order->id, 'url' => ['view', 'id' => $order->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="order-update">

    <?php $form = ActiveForm::begin() ?>

    <div class="box box-default">
        <div class="box-header with-border">Заказчик</div>
        <div class="box-body">
            <?= $form->field($model, 'note')->hiddenInput(['value' => 'text']) ?>
            <?= $form->field($model->customer, 'phone')->textInput() ?>
            <?= $form->field($model->customer, 'name')->textInput() ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Доставка</div>
        <div class="box-body">
            <?= $form->field($model->delivery, 'index')->textInput()->label('Индекс') ?>
            <?= $form->field($model->delivery, 'address')->textarea(['rows' => 3])->label('Адрес') ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
