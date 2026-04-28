<?php

namespace App\Services;

class DiscountStrategyFactory
{
    public function create(string $type): DiscountStrategy
    {
        return match (strtolower($type)) {
            '10%' => new PercentDiscountStrategy(10),
            '20%' => new PercentDiscountStrategy(20),
            default => new NoDiscountStrategy(),
        };
    }
}
