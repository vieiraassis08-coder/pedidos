<?php

namespace App\Repositories;

use App\Models\Pedido;

class PedidoRepository
{
    private static ?self $instance = null;
    private string $filePath;
    private array $pedidos;

    private function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->pedidos = $this->readStorage();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            $filePath = __DIR__ . '/../data/pedidos.json';
            self::$instance = new self($filePath);
        }

        return self::$instance;
    }

    public function findAll(): array
    {
        return array_values($this->pedidos);
    }

    public function save(Pedido $pedido): Pedido
    {
        if ($pedido->getId() === 0) {
            $pedido->setId($this->nextId());
        }

        $this->pedidos[$pedido->getId()] = $pedido;
        $this->persist();

        return $pedido;
    }

    public function delete(int $id): bool
    {
        if (!isset($this->pedidos[$id])) {
            return false;
        }

        unset($this->pedidos[$id]);
        $this->persist();

        return true;
    }

    private function readStorage(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $content = file_get_contents($this->filePath);
        $raw = json_decode($content ?: '[]', true);

        if (!is_array($raw)) {
            return [];
        }

        return array_reduce($raw, function (array $carry, array $item) {
            $pedido = Pedido::fromArray($item);
            $carry[$pedido->getId()] = $pedido;
            return $carry;
        }, []);
    }

    private function persist(): void
    {
        $data = array_map(fn(Pedido $pedido) => $pedido->toArray(), $this->pedidos);
        file_put_contents($this->filePath, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    }

    private function nextId(): int
    {
        $ids = array_map(fn(Pedido $pedido) => $pedido->getId(), $this->pedidos);
        return empty($ids) ? 1 : max($ids) + 1;
    }
}
