<?php

namespace App\Services\Repositories;

use App\Entities\User;

// TODO: probably these methods will be repeated, so create a RepositoryInterface
interface UserRepositoryInterface
{
    public function add(User $user): bool;
    public function findByEmail(string $email): ?User;
    public function deleteByEmail(string $email): bool;
}
