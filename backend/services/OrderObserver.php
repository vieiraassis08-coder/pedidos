<?php

namespace App\Services;

use App\Models\Pedido;

interface OrderObserver
{
    public function update(string $event, Pedido $pedido): void;
}
