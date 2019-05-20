<?php

namespace cabinet\cart\cost;

use cabinet\entities\shop\Discount as EntityDiscount;

final class Cost
{
    private $value;
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
            elseif($size <= $discount->size_products){
                return $this->getOrigin() - $discount->value;
            }
        }

        return $this->getOrigin() - 300;
    }

    public function getValueDisc(int $size): float
    {
        /** @var EntityDiscount $discount */
        foreach($this->getEntityDiscounts() as $discount){
            if($size == $discount->size_products){
                return $discount->value;
            }
        }

        return 0;
    }

    /**
     * @param string $code
     * @return float|int
     */
    public function getTotalDiscCode($code): float
    {
        /** @var $discount EntityDiscount */
        if($discount = EntityDiscount::find()->active()
            ->andWhere(['AND',
                'type' => EntityDiscount::TYPE_PROMO_CODE,
                'type_value' => EntityDiscount::TYPE_VALUE_PERCENT])
            ->andWhere(['code' => $code])
            ->one())
        {
            return $this->getOrigin() * $discount->value / 100;

        }else if($discount = EntityDiscount::find()->active()
            ->andWhere(['AND',
                'type' => EntityDiscount::TYPE_PROMO_CODE,
                'type_value' => EntityDiscount::TYPE_VALUE_NUMBER])
            ->andWhere(['code' => $code])
            ->one())
        {
            return $this->getOrigin() - $discount->value;
        }

        return 0;
    }

    /**
     * @return Discount[]
     */
    public function getDiscounts(): array
    {
        return $this->discounts;
    }

    public function getEntityDiscounts(): array
    {

        if($discounts = EntityDiscount::find()->active()
            ->andWhere(['AND', // ? where
                'type' => EntityDiscount::TYPE_SIZE_PROD,
                'type_value' => EntityDiscount::TYPE_VALUE_NUMBER
            ])->all())
        {
            return $discounts;
        }

        return [];
    }
}