<?php

namespace App\Models;

class ItemPedido
{
    private Produto $produto;
    private int $quantidade;

    public function __construct(Produto $produto, int $quantidade)
    {
        $this->produto = $produto;
        $this->quantidade = $quantidade;
    }

    public function getProduto(): Produto
    {
        return $this->produto;
    }

    public function getQuantidade(): int
    {
        return $this->quantidade;
    }

    public function getSubtotal(): float
    {
        return $this->produto->getPreco() * $this->quantidade;
    }

    public function toArray(): array
    {
        return [
            'produto' => $this->produto->toArray(),
            'quantidade' => $this->quantidade,
            'subtotal' => $this->getSubtotal(),
        ];
    }
}
