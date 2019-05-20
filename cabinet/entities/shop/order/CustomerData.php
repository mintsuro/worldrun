<?php

namespace cabinet\entities\shop\order;

class CustomerData
{
    public $name;
    public $phone;

    public function __construct($phone, $name)
    {
        $this->name = $name;
        $this->phone = $phone;
    }
}