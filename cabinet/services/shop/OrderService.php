<?php

namespace cabinet\services\shop;

use cabinet\cart\Cart;
use cabinet\cart\CartItem;
use cabinet\cart\cost\Discount;
use cabinet\entities\shop\Discount as DiscountEntity;
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
use Yii;
use yii\helpers\Json;

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

        if(!isset($session['promo_code'])) {
            if($this->cart->getItems() > 1){
                $cost = $this->cart->getCost()->getTotalDiscSizeProd($this->cart->getAmount());
            }else{
                $cost = $this->cart->getCost()->getOrigin();
            }
        }else{
            $valuePromoCode = $session['promo_code']['value'];
            if($session['promo_code']['type'] == DiscountEntity::TYPE_VALUE_NUMBER){
                // Рассчет скидки при числовом коэффиценте значения промокода
                $discountValue = $this->cart->getCost()->getTotalDiscSizeProd($this->cart->getAmount());
                $cost = $discountValue - $valuePromoCode;
            }elseif($session['promo_code']['type'] == DiscountEntity::TYPE_VALUE_PERCENT){
                // Рассчет скидки при процентном коэффиценте значения промокода
                $discountValue = $this->cart->getCost()->getTotalDiscSizeProd($this->cart->getAmount());
                $cost = $discountValue - $discountValue * $valuePromoCode / 100;
            }else{
                $discountValue = $this->cart->getCost()->getTotalDiscSizeProd($this->cart->getAmount());
                $cost = $discountValue - $valuePromoCode;
            }
        }

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
            $cost
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
            if(isset($session['promo_code'])){
                unset($session['promo_code']);
            }
        });

        return $order;
    }

    // Рассчет при активации промокода
    public function calcPromoCode(string $code, $size)
    {
        return $this->cart->getCost()->getTotalDiscCode($code, $size);
    }

    // Проверка и обработка промокода
    public function resultPromoCode(string $code)
    {
        $data = [];
        $session = Yii::$app->session;
        $discount = DiscountEntity::find()->active()->where(['type' => DiscountEntity::TYPE_PROMO_CODE])
            ->andWhere(['code' => $code])->one();

        if(is_null($discount)){
            $data['flag'] = false;
            $data['text'] = 'Такой промокод не зарегистрирован.';
            return Json::encode($data);
        }else{
            $code_session = $session['promo_code']['code'];
        }

        if($code != $code_session){
            try {
                $data['value'] = $this->calcPromoCode($code, $this->cart->getAmount());
                $data['flag'] = true;
                $data['text'] = 'Промокод активирован';
                return Json::encode($data);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                $data['flag'] = false;
                $data['text'] = 'Не удалось выполнить активацию.';
                return Json::encode($data);
            }
        }else{
            $data['flag'] = false;
            $data['text'] = 'Такой промокод уже активирован.';
            return Json::encode($data);
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