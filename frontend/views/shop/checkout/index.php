<?php

/* @var $this  yii\web\View */
/* @var $cart  \cabinet\cart\Cart */
/* @var $model \cabinet\forms\Shop\Order\OrderForm */
/* @var $race  \cabinet\entities\cabinet\Race */
/* @var $dataProvider \yii\data\DataProviderInterface */
/* @var $user \cabinet\entities\user\User */
/* @var $products \cabinet\entities\shop\product\Product[] */

use cabinet\helpers\PriceHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Регистрация на забег';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-index">
    <h3 style="margin-top: 0px;"><?= Html::encode($this->title) ?></h3>

    <div class="product-list">
        <div class="row">
            <h4 style="padding-left: 15px;">Подарки</h4>
            <?php  echo \yii\widgets\ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}",
                'itemView' => '_product',
                'viewParams' => ['cart' => $cart],
            ])  ?>

        </div>
    </div>

    <?php $form = ActiveForm::begin() ?>

    <div class="panel panel-default">
        <div class="panel-heading">Участник</div>
        <div class="panel-body">
            <?= $form->field($model->customer, 'firstName')->textInput(['value' => $user->profile->first_name]) ?>
            <?= $form->field($model->customer, 'lastName')->textInput(['value' => $user->profile->last_name]) ?>
            <?= $form->field($model->customer, 'city')->textInput(['value' => $user->profile->city]) ?>
            <?= $form->field($model->customer, 'sex')->textInput(['value' => $user->profile->sex]) ?>
            <?= $form->field($model->customer, 'age')->textInput(['value' => $user->profile->age]) ?>
            <?= $form->field($model->customer, 'phone')->textInput(['value' => $user->profile->phone]) ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Доставка</div>
        <div class="panel-body">
            <?= $form->field($model->delivery, 'index')->textInput(['value' => $user->profile->postal_code]) ?>
            <?= $form->field($model->delivery, 'address')->textInput(['value' => $user->profile->address_delivery]) ?>
        </div>
    </div>

    <div class="panel panel-default">
        <?= $form->field($model, 'note')->textInput() ?>
    </div>

    <?php $cost = $cart->getCost() ?>
    <table class="table table-bordered">
        <tr>
            <td class="text-right"><strong>Скидка:</strong></td>
            <td class="text-right">0</td>
        </tr>
        <tr>
            <td class="text-right"><strong>Итого:</strong></td>
            <?php if($cart->getItems()) : ?>
                <td class="text-right"><?= PriceHelper::format($cost->getOrigin()) . ' руб.' ?></td>
            <?php else : ?>
                <td class="text-right">0 руб.(бесплатно)</td>
            <?php endif; ?>
        </tr>
    </table>

    <div class="form-group text-center">
        <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end() ?>

</div>

