<?php

namespace App\Services;

use App\Repositories\PedidoRepository;

class PedidoService
{
    private PedidoRepository $repository;
    private OrderSubject $subject;
    private OrderLoggerObserver $logger;

    public function __construct()
    {
        $this->repository = PedidoRepository::getInstance();
        $this->subject = new OrderSubject();
        $this->logger = new OrderLoggerObserver();
        $this->subject->attach($this->logger);
    }

    public function listPedidos(): array
    {
        return array_map(fn($pedido) => $pedido->toArray(), $this->repository->findAll());
    }

    public function createPedido(array $data): array
    {
        $factory = new PedidoFactory();
        $pedido = $factory->createPedido($data);
        $saved = $this->repository->save($pedido);
        $this->subject->notify('pedido_criado', $saved);

        return [
            'pedido' => $saved->toArray(),
            'events' => $this->logger->getEvents(),
        ];
    }

    public function deletePedido(int $id): array
    {
        $deleted = false;
        $pedido = null;

        foreach ($this->repository->findAll() as $item) {
            if ($item->getId() === $id) {
                $pedido = $item;
                break;
            }
        }

        if ($pedido !== null) {
            $deleted = $this->repository->delete($id);
            $this->subject->notify('pedido_removido', $pedido);
        }

        return [
            'deleted' => $deleted,
            'events' => $this->logger->getEvents(),
        ];
    }
}
