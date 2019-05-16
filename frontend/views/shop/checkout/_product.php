<?php

/* @var $this yii\web\View */
/* @var $model \cabinet\entities\shop\product\Product */
/* @var $cart \cabinet\cart\Cart */

use yii\helpers\Html;
use yii\helpers\Url;
use cabinet\helpers\ProductHelper;

?>

<div class="col-sm-6">
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
                <span><?= Html::encode($model->price) ?> ₽</span>
            </div>
            <div class="">
                <?php foreach($cart->getItems() as $item){
                    if($item->getProductId() === $model->id ){
                        echo Html::a('Выбрано', Url::to(['/shop/cart/remove', 'id' => $item->getId()]), [
                            'class' => 'btn-choice active', 'data' => ['method' => 'post']
                        ]);
                        break;
                    }
                } ?>
                <?php echo Html::a('Выбрать', Url::to(['/shop/cart/add', 'id' => $model->id]), [
                'class' => 'btn-choice', 'data' => ['method' => 'post']
                ]); ?>
            </div>
        </div>
</div>

