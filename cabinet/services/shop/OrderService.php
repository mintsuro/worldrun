<?php

namespace cabinet\services\shop;

use cabinet\cart\Cart;
use cabinet\cart\CartItem;
use cabinet\entities\shop\order\CustomerData;
use cabinet\entities\shop\order\DeliveryData;
use cabinet\entities\shop\order\Order;
use cabinet\entities\shop\order\OrderItem;
use cabinet\forms\shop\order\OrderForm;
use cabinet\repositories\shop\DiscountRepository;
use cabinet\repositories\shop\OrderRepository;
use cabinet\readModels\shop\OrderReadRepository;
use cabinet\repositories\shop\ProductRepository;
use cabinet\repositories\UserRepository;
use cabinet\services\TransactionManager;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

class OrderService
{
    private $cart;
    private $orders;
    private $readOrders;
    private $products;
    private $users;
    private $transaction;
    private $discounts;

    public function __construct(
        Cart $cart,
        DiscountRepository $discounts,
        OrderRepository $orders,
        OrderReadRepository $readOrders,
        ProductRepository $products,
        UserRepository $users,
        TransactionManager $transaction
    )
    {
        $this->cart = $cart;
        $this->discounts = $discounts;
        $this->orders = $orders;
        $this->readOrders = $readOrders;
        $this->products = $products;
        $this->users = $users;
        $this->transaction = $transaction;
    }

    /**
     * @param $raceId
     * @param $userId
     * @param OrderForm $form
     * @return Order
     * @throws \Exception
     */
    public function checkout($raceId, $userId, OrderForm $form): Order
    {
        $user = $this->users->get($userId);
        $session = \Yii::$app->session;

        $products = [];
        $cost = function() use ($session){
            if(!$session->has('promo_code')) {
                if ($this->cart->getItems() > 1) {
                    return (int) $this->cart->getCost()->getTotalDiscSizeProd($this->cart->getAmount());
                } else {
                    return (int) $this->cart->getCost()->getOrigin();
                }
            }else{
                $valuePromoCode = (int) $session->get('promo_code');
                $discountValue = (int) $this->cart->getCost()->getTotalDiscSizeProd($this->cart->getAmount());
                return $discountValue - $valuePromoCode;
            }
        };

        $items = array_map(function (CartItem $item){
            $product = $item->getProduct();
            $products[] = $product;
            return OrderItem::create(
                $product,
                $item->getPrice()
            );
        }, $this->cart->getItems());

        $order = Order::create(
            $raceId,
            $user->id,
            new CustomerData(
                $form->customer->firstName . ' ' . $form->customer->lastName,
                $form->customer->phone
            ),
            $items,
            ($this->cart->getItems() > 1) ?
                $this->cart->getCost()->getTotalDiscSizeProd($this->cart->getAmount()) :
                $this->cart->getCost()->getOrigin() // $this->cart->getCost()->getTotal()
        );

        $order->setDeliveryInfo(
            new DeliveryData(
                $form->delivery->index,
                $form->delivery->address,
                $form->delivery->city
            )
        );

        $this->transaction->wrap(function() use ($order, $session){
            $this->orders->save($order);
            $this->cart->clear();
            if($session->has('promo_code')){
                $session->remove('promo_code');
            }
        });

        return $order;
    }

    // Рассчет при активации промокода
    public function calcPromoCode($code, $size): float
    {
        $discount = $this->discounts->getByCode($code);
        $session = \Yii::$app->session;
        if(!$session->has('promo_code')) {
            return $this->cart->getCost()->getTotalDiscCode($discount->code, $size);
        }else{
            throw new \DomainException('Такой промокод уже активирован.');
        }
    }

    public function pay($id, $payment_method): void
    {
        $order = $this->orders->get($id);
        $order->pay($payment_method);
        $this->orders->save($order);
    }

    public function fail($id, $reason): void
    {
        $order = $this->orders->get($id);
        $order->cancel($reason);
        $this->orders->save($order);
    }

    public function remove($id): void
    {
        $order = $this->orders->get($id);
        $this->orders->remove($order);
    }

    /** @param integer $id
     *  @param $payment_id
     *  Save to attribute "payment_id" for PaymentController (Yandex,kassa)
     */
    public function savePaymentId($id, $payment_id): void
    {
        $order = $this->orders->get($id);
        $order->setPaymentId($payment_id);
        $this->orders->save($order);
    }
}