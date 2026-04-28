<?php

require_once __DIR__ . '/../bootstrap.php';

use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Produto;
use App\Services\NoDiscountStrategy;
use App\Services\PercentDiscountStrategy;

function assertEqual($expected, $actual, string $message = ''): void
{
    if ($expected !== $actual) {
        echo "Falha: {$message}\nEsperado: ";
        var_export($expected);
        echo "\nObtido: ";
        var_export($actual);
        echo "\n";
        exit(1);
    }
}

$produto = new Produto(1, 'Teste', 50);
$item = new ItemPedido($produto, 2);
$pedido = new Pedido([$item]);
assertEqual(100.0, $pedido->calculateSubTotal(), 'Cálculo de subtotal');

$pedido->applyDiscount(new NoDiscountStrategy());
assertEqual(100.0, $pedido->getTotal(), 'Sem desconto retorna total igual ao subtotal');

$pedido->applyDiscount(new PercentDiscountStrategy(10));
assertEqual(90.0, $pedido->getTotal(), 'Aplicar desconto de 10%');

echo "Todos os testes passaram.\n";
