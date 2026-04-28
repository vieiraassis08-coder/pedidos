<?php

namespace App\Services;

class PercentDiscountStrategy implements DiscountStrategy
{
    private float $percentage;

    public function __construct(float $percentage)
    {
        $this->percentage = $percentage;
    }

    public function apply(float $amount): float
    {
        $discount = $amount * ($this->percentage / 100);
        return round($amount - $discount, 2);
    }
}
