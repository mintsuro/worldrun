<?php

namespace cabinet\cart\cost\calculator;

use cabinet\cart\cost\Cost;
use cabinet\cart\cost\Discount as CartDiscount;
use cabinet\entities\shop\Discount as EntityDiscount;

class DynamicCost implements CalculatorInterface
{
    private $next;

    public function __construct(CalculatorInterface $next)
    {
        $this->next = $next;
    }

    public function getCost(array $items): Cost
    {
        /** @var EntityDiscount[] $discounts */
        $discounts = EntityDiscount::find()->active()->orderBy('sort')->all();
        $cost = $this->next->getCost($items);

        foreach($discounts as $discount){
            if($discount->isEnabled()){
                $new = new CartDiscount($cost->getOrigin(), $discount->name);
                $cost = $cost->withDiscount($new);
            }
        }

        return $cost;
    }
}