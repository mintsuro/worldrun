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
        $countItems = count($items);

        foreach($discounts as $discount){
            if($discount->isEnabled()){
                /* if($countItems == 2){
                    $new = new CartDiscount($cost->getOrigin() - 200, $discount->name);
                    $cost = $cost->withDiscount($new);
                    var_dump($cost->getOrigin() - 200);
                    break;
                }elseif($countItems <= 3){
                    $new = new CartDiscount($cost->getOrigin() - 300, $discount->name);
                    $cost = $cost->withDiscount($new);
                    break;
                } */
                $new = new CartDiscount($cost->getOrigin(), $discount->name);
                $cost = $cost->withDiscount($new);
                //var_dump($discount->value);
            }
        }

        return $cost;
    }
}