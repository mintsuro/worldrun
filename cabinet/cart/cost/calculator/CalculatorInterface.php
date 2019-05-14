<?php

namespace cabinet\cart\cost\calculator;

use cabinet\cart\CartItem;
use cabinet\cart\cost\Cost;

interface CalculatorInterface
{
    /**
     * @param CartItem[] $items
     * @return Cost
     */
    public function getCost(array $items): Cost;
}