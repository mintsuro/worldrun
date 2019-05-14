<?php

namespace cabinet\entities\shop\order;

class DeliveryData
{
    public $index;
    public $address;

    public function __construct($index, $address)
    {
        $this->index = $index;
        $this->address = $address;
    }
}