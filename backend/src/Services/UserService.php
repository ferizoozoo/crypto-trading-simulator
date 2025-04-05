<?php

namespace App\Services;

use App\Controller\Services\UserServiceInterface;
use App\Entities\User;
use App\Services\Repositories\UserRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserService implements UserServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(UserRepositoryInterface $userRepository, JWTTokenManagerInterface $jwtManager)
    {
        $this->userRepository = $userRepository;
        $this->jwtManager = $jwtManager;
    }

    public function register(User $user): bool
    {
        return $this->userRepository->add($user);
    }

    public function authenticate(User $user): ?string
    {
        return $this->jwtManager->create($user);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function deleteByEmail(string $email): bool
    {
        return $this->userRepository->deleteByEmail($email);
    }

    public function updateBalance(int $userId, float $amount)
    {
        $user = $this->userRepository->findById($userId);
        $user->setBalance($user->getBalance() + $amount);
        $this->userRepository->update($user);
    }

    public function getUserBalance(int $userId)
    {
        $user = $this->userRepository->findById($userId);
        if ($user === null) {
            dd('user not found ', $userId);
        }
        return $user->getBalance();
    }
}
