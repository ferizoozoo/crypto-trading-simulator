<?php

namespace App\Services\Repositories;

use App\Entities\User;

interface UserRepositoryInterface
{
    public function register(User $user): bool;
}
