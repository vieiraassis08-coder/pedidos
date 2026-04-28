<?php

namespace App\Services;

use App\Models\Pedido;

class OrderSubject
{
    private array $observers = [];

    public function attach(OrderObserver $observer): void
    {
        $this->observers[] = $observer;
    }

    public function notify(string $event, Pedido $pedido): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($event, $pedido);
        }
    }
}
