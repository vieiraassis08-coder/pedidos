<?php

namespace App\Services;

interface DiscountStrategy
{
    public function apply(float $amount): float;
}
