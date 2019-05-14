<?php

namespace cabinet\repositories\shop;

use cabinet\entities\shop\order\Order;
use cabinet\repositories\NotFoundException;

class OrderRepository
{
    public function get($id): Order
    {
        if (!$order = Order::findOne($id)) {
            throw new NotFoundException('Заказ не найден.');
        }
        return $order;
    }

    public function save(Order $order): void
    {
        if (!$order->save()) {
            throw new \RuntimeException('Ошибка при сохранении.');
        }
    }

    public function remove(Order $order): void
    {
        if (!$order->delete()) {
            throw new \RuntimeException('Ошибка при удалении.');
        }
    }
}