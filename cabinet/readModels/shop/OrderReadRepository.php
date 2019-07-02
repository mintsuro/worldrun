<?php

namespace cabinet\readModels\shop;

use cabinet\entities\shop\order\Order;
use cabinet\entities\shop\order\Status;
use yii\data\ActiveDataProvider;
use cabinet\repositories\NotFoundException;

class OrderReadRepository
{
    public function getOwm($userId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Order::find()
                ->andWhere(['user_id' => $userId])
                ->orderBy(['id' => SORT_DESC]),
            'sort' => false,
        ]);
    }

    public function findOwn($userId, $id): ?Order
    {
        return Order::find()->andWhere(['user_id' => $userId, 'id' => $id])->one();
    }

    public function findByRace($raceId): ?Order
    {
        return Order::find()->andWhere(['race_id' => $raceId])->one();
    }

    public function findByPaymentId(string $paymentId): ?Order
    {
        if(!$order = Order::find()->andWhere(['payment_id' => $paymentId])->limit(1)->one())
        {
            throw new NotFoundException('Заказ с таким идентификатором оплаты не найден.');
        }
        return $order;
    }

    public function getNewAll(): array
    {
        $orders = Order::find()->andWhere(['current_status' => Status::NEW, 'notify_send' => 0])->all();

        return $orders;
    }
}