<?php

namespace App\Services;

use App\Models\Pedido;

class OrderLoggerObserver implements OrderObserver
{
    private array $events = [];
    private string $logFile;

    public function __construct()
    {
        $this->logFile = __DIR__ . '/../data/events.log';
    }

    public function update(string $event, Pedido $pedido): void
    {
        $message = sprintf("Evento: %s - Pedido %d - total R$ %.2f", $event, $pedido->getId(), $pedido->getTotal());
        $this->events[] = $message;
        file_put_contents($this->logFile, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public function getEvents(): array
    {
        return $this->events;
    }
}
