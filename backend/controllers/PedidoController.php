<?php

namespace App\Controllers;

use App\Services\PedidoService;

class PedidoController
{
    private PedidoService $service;

    public function __construct()
    {
        $this->service = new PedidoService();
    }

    public function index(): void
    {
        $pedidos = $this->service->listPedidos();
        echo json_encode(['success' => true, 'data' => $pedidos]);
    }

    public function create(): void
    {
        $payload = json_decode(file_get_contents('php://input'), true);

        if (!is_array($payload)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
            return;
        }

        $result = $this->service->createPedido($payload);
        echo json_encode(['success' => true, 'data' => $result['pedido'], 'events' => $result['events']]);
    }

    public function delete(): void
    {
        $payload = json_decode(file_get_contents('php://input'), true);

        if (!is_array($payload) || empty($payload['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'ID do pedido obrigatório']);
            return;
        }

        $result = $this->service->deletePedido((int) $payload['id']);

        if (!$result['deleted']) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Pedido não encontrado']);
            return;
        }

        echo json_encode(['success' => true, 'events' => $result['events']]);
    }
}
