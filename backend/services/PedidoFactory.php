<?php

namespace App\Services;

use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Produto;

class PedidoFactory
{
    private DiscountStrategyFactory $strategyFactory;

    public function __construct()
    {
        $this->strategyFactory = new DiscountStrategyFactory();
    }

    public function createPedido(array $data): Pedido
    {
        $items = array_map(function (array $itemData) {
            $produto = new Produto(
                (int) ($itemData['produtoId'] ?? 0),
                (string) ($itemData['nome'] ?? ''),
                round((float) ($itemData['preco'] ?? 0), 2)
            );

            return new ItemPedido($produto, max(1, (int) ($itemData['quantidade'] ?? 1)));
        }, $data['itens'] ?? []);

        $discountType = (string) ($data['discountType'] ?? 'none');
        $strategy = $this->strategyFactory->create($discountType);

        $pedido = new Pedido($items, $discountType);
        $pedido->applyDiscount($strategy);

        return $pedido;
    }
}
