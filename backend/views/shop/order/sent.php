<?php

/* @var $this yii\web\View */
/* @var $order cabinet\entities\shop\order\Order */
/* @var $model cabinet\forms\manage\shop\order\OrderSentForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Подтвердить отправку заказа: ' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $order->id, 'url' => ['view', 'id' => $order->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="order-update">

    <?php $form = ActiveForm::begin() ?>

    <em>После подтверждения будет отправлен email заказчику об отправке заказа.</em>

    <div class="box box-default" style="margin-top: 10px">
        <div class="box-body">
            <?= $form->field($model, 'track_post')->textInput() ?>
            <?= $form->field($model, 'status')->dropDownList(\cabinet\helpers\OrderHelper::statusSent()) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Подтвердить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
