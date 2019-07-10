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
            <?= Html::img(\Yii::$app->get('frontendUrlManager')->baseUrl . '/uploads/origin/product/' . "$model->id-$model->photo",
                ['class' => 'img-responsive']) ?>
        <?php endif; ?>
    </div>
    <div class="product-name">
        <span><?= Html::encode($model->name) ?></span>
        <?= Html::encode($model->description) ?>
    </div>
    <div class="product-price">
        <span class="price-val"><?= Html::encode($model->price) ?> </span><span class="symbol-price">P</span>
    </div>
    <?php if($model->price > 0) echo ProductHelper::buttonCart($cart, $model); ?>
</div>

<?php $this->registerJs("
   
    
    // Добавление товара в корзину без перезагрузки страницы
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
            beforeSend: function(){
                $(element).parent($('.product-item')).find('.loader').show();
            },
            complete: function(){
                $(element).parent($('.product-item')).find('.loader').hide();
            }
        });
    });
", $this::POS_END);
?>