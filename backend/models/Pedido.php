<?php

namespace App\Models;

use App\Services\DiscountStrategy;

class Pedido
{
    private int $id;
    private array $itens;
    private float $total;
    private string $discountType;

    public function __construct(array $itens = [], string $discountType = 'none', int $id = 0)
    {
        $this->itens = $itens;
        $this->discountType = $discountType;
        $this->id = $id;
        $this->total = 0.0;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getItens(): array
    {
        return $this->itens;
    }

    public function getDiscountType(): string
    {
        return $this->discountType;
    }

    public function calculateSubTotal(): float
    {
        return array_reduce($this->itens, function (float $carry, ItemPedido $item) {
            return $carry + $item->getSubtotal();
        }, 0.0);
    }

    public function applyDiscount(DiscountStrategy $strategy): void
    {
        $this->total = $strategy->apply($this->calculateSubTotal());
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'itens' => array_map(fn(ItemPedido $item) => $item->toArray(), $this->itens),
            'discountType' => $this->discountType,
            'total' => $this->total,
        ];
    }

    public static function fromArray(array $data): self
    {
        $itens = array_map(function (array $itemData) {
            $produto = new Produto(
                (int) ($itemData['produto']['id'] ?? 0),
                (string) ($itemData['produto']['nome'] ?? ''),
                (float) ($itemData['produto']['preco'] ?? 0)
            );

            return new ItemPedido($produto, (int) ($itemData['quantidade'] ?? 0));
        }, $data['itens'] ?? []);

        $pedido = new self($itens, (string) ($data['discountType'] ?? 'none'), (int) ($data['id'] ?? 0));
        $pedido->total = (float) ($data['total'] ?? $pedido->calculateSubTotal());

        return $pedido;
    }
}
