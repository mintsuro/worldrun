<?php

namespace cabinet\cart\cost;

final class Discount
{
    private $value;
    private $name;

    public function __construct($value, string $name)
    {
        $this->value = $value;
        $this->name = $name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }
}