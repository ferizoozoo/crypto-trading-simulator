<?php

namespace App\Controller\Services;

use App\Entities\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

interface UserServiceInterface
{
    public function register(User $user);
    public function authenticate(User $user): ?string;
    public function updateBalance(User $user, float $amount);
    public function getUserBalance(int $userId);
    public function findByEmail(string $email): ?User;
    public function deleteByEmail(string $email): bool;
}
