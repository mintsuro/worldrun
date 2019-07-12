<?php

namespace cabinet\services\shop;

use cabinet\cart\Cart;
use cabinet\cart\CartItem;
use cabinet\cart\cost\Discount;
use cabinet\entities\shop\Discount as EntityDiscount;
use cabinet\repositories\shop\ProductRepository;
use cabinet\helpers\PriceHelper;
use yii\helpers\Json;
use yii\helpers\Url;

class CartService
{
    private $cart;
    private $products;

    public function __construct(Cart $cart, ProductRepository $products)
    {
        $this->cart = $cart;
        $this->products = $products;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function add($productId): void
    {
        $product = $this->products->get($productId);
        $this->cart->add(new CartItem($product));
    }

    /* public function set($id): void
    {
        $this->cart->set($id);
    } */

    public function remove($id): void
    {
        $this->cart->remove($id);
    }

    public function clear(): void
    {
        $this->cart->clear();
    }

    // Рассчет общей стоимости выбранных товаров при добавлении товара
    public function ajaxCalculateTotal($id = null)
    {
        $session = \Yii::$app->session;
        $cart = $this->cart;
        $cost = $cart->getCost();
        $items = $cart->getItems();
        $flag = $items ? true : false;
        $data = [];
        $data['url'] = Url::to(['/shop/cart/add', 'id' => $id]);
        $data['flag'] = $flag;
        $data['amount'] = $cart->getAmount();

        if(isset($session['promo_code']) && $items){
            if($session['promo_code']['type'] == EntityDiscount::TYPE_VALUE_NUMBER){
                $data['discount'] = PriceHelper::format($cost->getValueDisc($cart->getAmount()) + $session['promo_code']['value']);
                $data['total'] = PriceHelper::format($cost->getTotalDiscSizeProd($cart->getAmount()) - $session['promo_code']['value']);
            }elseif($session['promo_code']['type'] == EntityDiscount::TYPE_VALUE_PERCENT){
                $data['discount'] = PriceHelper::format(ceil($cost->getValueDisc($cart->getAmount())
                    + $cost->getValueDisc($cart->getAmount()) * $session['promo_code']['value'] / 100));
                $data['total'] = PriceHelper::format(ceil($cost->getTotalDiscSizeProd($cart->getAmount())
                    - $cost->getTotalDiscSizeProd($cart->getAmount()) * $session['promo_code']['value'] / 100));
            }else{
                $data['discount'] = PriceHelper::format($cost->getValueDisc($cart->getAmount()) + $session['promo_code']['value']);
                $data['total'] = PriceHelper::format($cost->getTotalDiscSizeProd($cart->getAmount()) - $session['promo_code']['value']);
            }

        }elseif($items){
            $data['discount'] = PriceHelper::format($cost->getValueDisc($cart->getAmount()));
            $data['total'] = PriceHelper::format($cost->getTotalDiscSizeProd($cart->getAmount()));
        }else{
            $data['discount'] = 0;
            $data['total'] = 0 . ' руб.(бесплатно)';
        }

        foreach($cart->getItems() as $item){
            if($item->getProductId() == $id){
                $data['url'] = Url::to(['/shop/cart/remove', 'id' => $item->getId()]);
            }
        }

        return Json::encode($data);
    }
}