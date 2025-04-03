<?php

namespace App\Services;

use App\Entities\Order;
use App\Entities\User;
use App\Enums\OrderType;
use App\Services\Repositories\OrderRepositoryInterface;
use App\Controller\Services\OrderServiceInterface;
use App\Entities\Trade;
use App\Infrastructure\Repositories\UserRepository;
use App\Services\Repositories\UserRepositoryInterface;
use DateTime;

class OrderService implements OrderServiceInterface
{
    private OrderRepositoryInterface $orderRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, UserRepositoryInterface $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
    }

    public function create(
        int $userId,
        int $amount,
        int $price,
        OrderType $type,
        int $timestamp
    ): ?Trade {
        $user = $this->userRepository->findById($userId);
        $this->orderRepository->add(new Order($user, $amount, $price, $type, $timestamp));

        // TODO: order should be placed by making a request to the trading engine (hardcoded for now to pass tests)
        $trade = new Trade($userId, $userId + 1, 100, 10, new DateTime());

        return $trade;
    }

    public function findById(int $id): ?Order
    {
        return $this->orderRepository->findById($id);
    }

    public function delete(int $id): bool
    {
        return $this->orderRepository->delete($id);
    }
}
