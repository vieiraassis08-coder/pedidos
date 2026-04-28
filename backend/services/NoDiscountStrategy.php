<?php

namespace App\Services;

class NoDiscountStrategy implements DiscountStrategy
{
    public function apply(float $amount): float
    {
        return $amount;
    }
}
