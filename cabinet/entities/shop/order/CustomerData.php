<?php

namespace cabinet\entities\shop\order;

class CustomerData
{
    public $name;
    public $phone;

    public function __construct($name, $phone)
    {
        $this->name = $name;
        $this->phone = $phone;
    }
}