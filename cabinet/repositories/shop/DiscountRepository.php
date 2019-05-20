<?php

namespace cabinet\repositories\shop;

use cabinet\entities\shop\Discount;
use cabinet\repositories\NotFoundException;

class DiscountRepository
{
    public function get($id): Discount
    {
        if (!$discount = Discount::findOne($id)) {
            throw new NotFoundException('Скидка не найдена.');
        }
        return $discount;
    }

    public function getByCode($code): Discount
    {
        if (!$discount = Discount::findOne(['code' => $code])) {
            throw new NotFoundException('Такой промокод не зарегистрирован.');
        }
        return $discount;
    }

    public function save(Discount $discount): void
    {
        if (!$discount->save()) {
            throw new \RuntimeException('Ошибка при сохранении.');
        }
    }

    public function remove(Discount $discount): void
    {
        if (!$discount->delete()) {
            throw new \RuntimeException('Ошибка при удалении.');
        }
    }
}