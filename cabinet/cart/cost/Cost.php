<?php

namespace cabinet\cart\cost;

use cabinet\entities\shop\Discount as EntityDiscount;
use cabinet\cart\Cart;
use cabinet\services\shop\CartService;
use yii\helpers\Json;

final class Cost
{
    private $value;
    private $cart;
    private $discounts = [];

    public function __construct($value, array $discounts = [])
    {
        $this->value = $value;
        $this->discounts = $discounts;
    }

    public function withDiscount(Discount $discount): self
    {
        return new static($this->value, array_merge($this->discounts, [$discount]));
    }

    public function getOrigin(): float
    {
        return $this->value;
    }

    public function getTotal(): float
    {
        return $this->value - array_sum(array_map(function (Discount $discount){
                return $discount->getValue();
            }, $this->discounts));
    }

    /**
     * Провести рассчет по скидке на кол-во выбранных товаров
     * @param int $size
     * @return float
     * Calculate Discount to select products
     */
    public function getTotalDiscSizeProd(int $size): float
    {
        if($size == 1){
            return $this->getOrigin();
        }

        /** @var EntityDiscount $discount */
        foreach($this->getEntityDiscounts() as $discount){
            if($size == $discount->size_products){
                return $this->getOrigin() - $discount->value;
            }
            /*elseif($size <= $discount->size_products){
                return $this->getOrigin() - $discount->value;
            }*/
        }

        //return $this->getOrigin() - 300;
        return $this->getOrigin();
    }

    public function getValueDisc(int $size): float
    {
        /** @var EntityDiscount $discount */
        foreach($this->getEntityDiscounts() as $discount){
            if($size == $discount->size_products){
                return $discount->value;
            }elseif($size > 3){
                return 300; // временная загушка (добавить поле скидка по умолчанию)
            }
        }

        return 0;
    }

    /**
     * Рассчет скидки при активации промокода
     * @param string $code
     * @param integer $size
     * @return string
     */
    public function getTotalDiscCode($code, $size)
    {
        /** @var $discount EntityDiscount */
        try{
            if($discount = EntityDiscount::find()->active() // Рассчет в процентном соотношении
                ->andWhere(['AND',
                    'type' => EntityDiscount::TYPE_PROMO_CODE,
                    'type_value' => EntityDiscount::TYPE_VALUE_PERCENT])
                ->andWhere(['code' => $code])
                ->one())
            {
                $sum = ceil($this->getTotalDiscSizeProd($size) * $discount->value / 100); // (int) 35
                $total = $this->getTotalDiscSizeProd($size) - $sum;
                \Yii::$app->session->set('promo_code', $sum);
                return Json::encode($sum);

            }else if($discount = EntityDiscount::find()->active()  // Рассчет в числовом соотношении
                ->andWhere(['AND',
                    'type' => EntityDiscount::TYPE_PROMO_CODE,
                    'type_value' => EntityDiscount::TYPE_VALUE_NUMBER])
                ->andWhere(['code' => $code])
                ->one())
            {
                $sum = ceil($this->getTotalDiscSizeProd($size) - $discount->value);
                \Yii::$app->session->set('promo_code', $sum);
                return Json::encode($sum);
            }
        }catch(\DomainException $e){
            throw new \DomainException('Такой промокод не найден.');
        }
    }

    /**
     * @return Discount[]
     */
    public function getDiscounts(): array
    {
        return $this->discounts;
    }

    // Получить все скидки на товары при выборе n-ых чисел товаров
    public function getEntityDiscounts(): array
    {
        if($discounts = EntityDiscount::find()->active()
            ->andWhere(['AND',
                'type' => EntityDiscount::TYPE_SIZE_PROD,
                'type_value' => EntityDiscount::TYPE_VALUE_NUMBER
            ])->all())
        {
            return $discounts;
        }

        return [];
    }
}