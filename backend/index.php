<?php

require_once __DIR__ . '/bootstrap.php';

use App\Controllers\PedidoController;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$controller = new PedidoController();

if ($uri === '/pedidos') {
    if ($method === 'GET') {
        $controller->index();
        return;
    }

    if ($method === 'POST') {
        $controller->create();
        return;
    }

    if ($method === 'DELETE') {
        $controller->delete();
        return;
    }
}

http_response_code(404);
echo json_encode(['error' => 'Rota não encontrada']);
