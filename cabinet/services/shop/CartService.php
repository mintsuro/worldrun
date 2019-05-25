<?php

namespace cabinet\services\shop;

use cabinet\cart\Cart;
use cabinet\cart\CartItem;
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

    public function ajaxCalculateTotal($id = null){
        $session = \Yii::$app->session;
        $cart = $this->cart;
        $cost = $cart->getCost();
        $items = $cart->getItems();
        $flag = $items ? true : false;
        $data = [];
        $data['url'] = Url::to(['/shop/cart/add', 'id' => $id]); // дополнить параметр id
        $data['flag'] = $flag;

        if($session->has('promo_code') && $items){
            $data['discount'] = PriceHelper::format($cost->getValueDisc($cart->getAmount()) + $session->get('promo_code'));
            $data['total'] = PriceHelper::format($cost->getTotalDiscSizeProd($cart->getAmount()) - $session->get('promo_code'));
        }elseif ($items){
            $data['discount'] = PriceHelper::format($cost->getValueDisc($cart->getAmount()));
            $data['total'] = PriceHelper::format($cost->getTotalDiscSizeProd($cart->getAmount()));
        }else{
            $data['discount'] = 0;
            $data['total'] = 0 . ' руб.(бесплатно)';
        }

        foreach($cart->getItems() as $item){
            if($item->getProductId() == $id){
                $data['url'] = Url::to(['/shop/cart/remove', 'id' => $item->getId()]);
                //break;
            }
        }

        return Json::encode($data);
    }
}