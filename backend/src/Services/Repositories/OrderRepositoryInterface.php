<?php

namespace App\Services\Repositories;

use App\Entities\Order;

// TODO: probably these methods will be repeated, so create a RepositoryInterface
interface OrderRepositoryInterface
{
    public function add(Order $order): ?Order;
    public function findById(int $id): ?Order;
    public function delete(int $id): bool;
}
