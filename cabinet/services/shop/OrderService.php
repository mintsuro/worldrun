<?php

namespace cabinet\services\shop;

use cabinet\cart\Cart;
use cabinet\cart\CartItem;
use cabinet\entities\shop\order\CustomerData;
use cabinet\entities\shop\order\DeliveryData;
use cabinet\entities\shop\order\Order;
use cabinet\entities\shop\order\OrderItem;
use cabinet\forms\shop\order\OrderForm;
use cabinet\repositories\shop\OrderRepository;
use cabinet\repositories\shop\ProductRepository;
use cabinet\repositories\UserRepository;
use cabinet\services\TransactionManager;

class OrderService
{
    private $cart;
    private $orders;
    private $products;
    private $users;
    private $transaction;

    public function __construct(
        Cart $cart,
        OrderRepository $orders,
        ProductRepository $products,
        UserRepository $users,
        TransactionManager $transaction
    )
    {
        $this->cart = $cart;
        $this->orders = $orders;
        $this->products = $products;
        $this->users = $users;
        $this->transaction = $transaction;
    }

    public function checkout($userId, OrderForm $form): Order
    {
        $user = $this->users->get($userId);

        $products = [];

        $items = array_map(function (CartItem $item){
            $product = $item->getProduct();
            $products[] = $product;
            return OrderItem::create(
                $product,
                $item->getPrice()
            );
        }, $this->cart->getItems());

        $order = Order::create(
            $user->id,
            new CustomerData(
                $form->customer->firstName . ' ' . $form->customer->lastName,
                $form->customer->phone
            ),
            $items,
            $this->cart->getCost()->getTotal()
        );

        $order->setDeliveryInfo(
            new DeliveryData(
                $form->delivery->index,
                $form->delivery->address
            )
        );

        $this->transaction->wrap(function() use ($order){
            $this->orders->save($order);
            $this->cart->clear();
        });

        return $order;
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
}