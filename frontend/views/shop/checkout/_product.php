<?php

/**
 * @var $this yii\web\View
 * @var $model \cabinet\entities\shop\product\Product
 * @var $cart \cabinet\cart\Cart
 * @var $race \cabinet\entities\cabinet\Race
*/

use yii\helpers\Html;
use yii\helpers\Url;
use cabinet\helpers\ProductHelper;

?>

<div class="product-item">
    <div class="center thumb">
        <?php if ($model->photo): ?>
            <?= Html::img(\Yii::$app->get('frontendUrlManager')->baseUrl . '/uploads/origin/product/' . $model->photo,
                ['style' => ['width' => '200px', 'height' => '200px'], 'class' => 'img-responsive']) ?>
        <?php endif; ?>
    </div>
    <div class="product-name">
        <span><?= Html::encode($model->name) ?></span>
        <?= Html::encode($model->description) ?>
    </div>
    <div class="product-price">
        <span class="price-val"><?= Html::encode($model->price) ?> </span>₽
    </div>
    <div class="">
        <?php foreach($cart->getItems() as $item){
            if($item->getProductId() == $model->id ){
                echo Html::tag('span', 'Выбрано', [
                    'class' => 'btn-choice remove-cart active',
                    'data-url' => Url::to(['/shop/cart/remove', 'id' => $item->getId()]),
                    'data-id' => $item->getId(),
                    'data-product-id' => $model->id,
                    'data' => ['method' => 'post'],
                ]);
                break;
            }
        }
        echo Html::tag('span', 'Выбрать', [
            'class' => 'btn-choice add-cart', 'id' => 'add-cart',
            'data-url' => Url::to(['/shop/cart/add', 'id' => $model->id]),
            'data-product-id' => $model->id,
            'data' => ['method' => 'post'],
        ]);?>
    </div>
</div>

<?php

$this->registerJs("
    // Заглушка кнопок для бесплатных товаров
    if($('.product-item .product-price .price-val').val() == 0 ){
        $('.product-index').eq(0).find($('.btn-choice')).removeClass('add-cart btn-choice').addClass('remove-cart active btn-choice-dis');
        $('.product-index').eq(1).find($('.btn-choice')).removeClass('add-cart btn-choice').addClass('remove-cart active btn-choice-dis');
    } 
    
    jQuery('.btn-choice').click(function(e){
       e.preventDefault();
       var path = $(this).attr('data-url');
       var productId =  $(this).attr('data-product-id');
       var element = this;
        
        $.ajax({
            url: path,
            type: 'POST',
            data: { product_id: productId },
            dataType: 'json',
            success: function(data){
                $('.discount-info').text(data.discount);
                $('.total-info').text(data.total);   
                $('#count-cart').val(data.amount);
                
                if(parseInt($('#count-cart').val()) == parseInt(0)){
                    $('#success-block').html('". Html::a("Зарегистрироваться", Html::encode(Url::to(["/cabinet/participation/add", "raceId" => $race->id])),
        ['class' => 'btn btn-primary btn-lg']) . "');
                }else{
                    $('#success-block').html('". Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary btn-lg']) ."');
                }
                
                if(data.flag){
                    $('#input-value-code').removeAttr('disabled');    
                    $('.active-promocode').removeClass('hidden').addClass('show');
                }else{
                    $('#input-value-code').attr('disabled', 'true');
                    $('.active-promocode').removeClass('show').addClass('hidden');
                }
                
                if(!jQuery(element).hasClass('active')){
                    jQuery(element).removeClass('add-cart');
                    jQuery(element).addClass('remove-cart active');
                    jQuery(element).text('Выбрано');
                }else{
                    jQuery(element).removeClass('remove-cart active');
                    jQuery(element).addClass('add-cart');
                    jQuery(element).text('Выбрать');
                }
                
                jQuery(element).attr('data-url', data.url);
            },
        });
    });
", $this::POS_END);

?>