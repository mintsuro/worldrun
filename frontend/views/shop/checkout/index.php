<?php

/* @var $this  yii\web\View */
/* @var $cart  \cabinet\cart\Cart */
/* @var $model \cabinet\forms\Shop\Order\OrderForm */
/* @var $modelCode \cabinet\forms\shop\order\PromoCodeForm */
/* @var $race  \cabinet\entities\cabinet\Race */
/* @var $dataProvider \yii\data\DataProviderInterface */
/* @var $user \cabinet\entities\user\User */
/* @var $products \cabinet\entities\shop\product\Product[] */
/* @var $discountCode \cabinet\entities\shop\Discount */

use cabinet\helpers\PriceHelper;
use cabinet\helpers\ProfileHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Регистрация на забег';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-index">
    <?php $cost = $cart->getCost();
    $items = $cart->getItems();
    $session = Yii::$app->session;
    ?>

    <h3 class="title-race-detail"><?= Html::encode($this->title) ?></h3>
    <?= $this->render('_race', [
        'race' => $race,
    ]) ?>

    <?php $form = ActiveForm::begin() ?>

    <div class="panel panel-default">
        <div class="panel-heading">Данные для регистрации</div>
        <div class="panel-body">
            <?= $form->field($model->customer, 'firstName')->textInput(['value' => $user->profile->first_name, 'readonly' => true]) ?>
            <?= $form->field($model->customer, 'lastName')->textInput(['value' => $user->profile->last_name, 'readonly' => true]) ?>
            <?= $form->field($model->customer, 'city')->textInput(['value' => $user->profile->city, 'readonly' => true]) ?>
            <?= $form->field($model->customer, 'age')->textInput(['value' => $user->profile->age, 'readonly' => true]) ?>
            <?= $form->field($model->customer, 'phone')->textInput(['value' => $user->profile->phone, 'readonly' => true]) ?>
        </div>
    </div>

    <div class="product-list">
        <div class="row">
            <h4 style="padding-left: 15px;">Подарки</h4>
            <?php  echo \yii\widgets\ListView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}",
                'itemView' => '_product',
                'viewParams' => ['cart' => $cart, 'race' => $race],
                'itemOptions' => ['class' => 'col-sm-6 product-index']
            ])  ?>
        </div>
        <?= Html::hiddenInput('countItems', $cart->getAmount(), ['id' => 'count-cart']) ?>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Доставка</div>
        <div class="panel-body">
            <?= $form->field($model->delivery, 'index')->textInput(['value' => $user->profile->postal_code]) ?>
            <?= $form->field($model->delivery, 'address')->textInput(['value' => $user->profile->address_delivery]) ?>
            <?= $form->field($model->delivery, 'city')->textInput(['value' => $user->profile->city_delivery]) ?>
        </div>
    </div>

    <?php if($discountCode){ ?>
    <div class="panel panel-default">
        <div class="panel-heading">Купон на скидку</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4">
                    <?php $disabled = $items ? false : true;
                    echo Html::activeTextInput($modelCode, 'code', [
                        'class' => 'form-control',
                        'id' => 'input-value-code',
                        'disabled' => $disabled
                    ]);  ?>
                </div>
                <div class="col-sm-3">
                    <?php  $class = $items ? 'show' : 'hidden';
                     echo Html::button('Активировать', [
                        'class' => 'active-promocode btn btn-success ' . $class,
                        'id' => 'active-promocode']); ?>
                </div>
                <div class="col-sm-4">
                    <div class="code-status">Неверный промокод или он уже активирован.</div>
                </div>
            </div>
            <p style="padding-top: 10px">Для активации промокода выберите подарок</p>
        </div>
    </div>
    <?php } ?>

    <!-- значение по умолчанию для отправки формы заказа -->
    <?= $form->field($model, 'note')->hiddenInput(['value' => 'text'])->label(false) ?>

    <table class="table table-bordered">
        <tr>
            <td class="text-right"><strong>Скидка:</strong></td>
            <?php if($session->has('promo_code') && $items) : ?>
                <td class="text-right"><span class="discount-info">
                    <span class="numb"><?= PriceHelper::format($cost->getValueDisc($cart->getAmount()) + $session->get('promo_code')) ?></span> руб.
                </span></td>
            <?php  elseif($items) : ?>
                <td class="text-right"><span class="discount-info">
                    <span class="numb"><?= PriceHelper::format($cost->getValueDisc($cart->getAmount())) ?></span> руб.
                </span></td>
            <?php else : ?>
                <td class="text-right"><span class="discount-info"><span class="numb">0</span> руб.</span></td>
            <?php endif; ?>
        </tr>
        <tr>
            <td class="text-right"><strong>Итого:</strong></td>
            <?php if($session->has('promo_code') && $items) : ?>
                <td class="text-right"><span class="total-info">
                    <span class="numb"><?= PriceHelper::format($cost->getTotalDiscSizeProd($cart->getAmount()) - $session->get('promo_code')) ?></span> руб.
                </span></td>
            <?php elseif($items) : ?>
                <td class="text-right"><span class="total-info">
                    <span class="numb"><?= PriceHelper::format($cost->getTotalDiscSizeProd($cart->getAmount())) ?></span> руб.
                </span></td>
            <?php else : ?>
                <td class="text-right"><span class="total-info"><span class="numb">0</span> руб.(бесплатно)</span></td>
            <?php endif; ?>
        </tr>
    </table>

    <div class="form-group text-center" id="success-block">
        <?php if($items) : ?>
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary btn-lg']) ?>
        <?php else : ?>
            <?= Html::a('Зарегистрироваться', Html::encode(Url::to(['/cabinet/participation/add', 'raceId' => $race->id])),
                ['class' => 'btn btn-primary btn-lg']) ?>
        <?php endif; ?>
    </div>
    <?php $form::end() ?>
</div>

<?php

$this->registerJs('
    // Активация промокода 
     jQuery("#active-promocode-test").click(function(e){
       var path = "'. (string) Url::to(['/shop/checkout/code']) .'";
       var element = this;
       var valueCode = jQuery("#input-value-code").val(); 
        
        $.post(path, {code: valueCode}, function( data ) {
          console.log( "Data Loaded: " + data );
        }, "json");
    });    
', $this::POS_END);
?>
