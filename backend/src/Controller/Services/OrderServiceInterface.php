<?php

namespace App\Controller\Services;

use App\Entities\Trade;
use App\Enums\OrderType;

interface OrderServiceInterface
{
    public function create(int $userId, int $amount, int $price, OrderType $type, int $timestamp): ?Trade;
}
