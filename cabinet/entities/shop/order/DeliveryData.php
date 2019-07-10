<?php

namespace cabinet\entities\shop\order;

class DeliveryData
{
    public $index;
    public $address;
    public $city;

    public function __construct($index, $address, $city)
    {
        $this->index = $index;
        $this->address = $address;
        $this->city = $city;
    }
}