<?php

namespace App\Services;

use App\Controller\Services\UserServiceInterface;
use App\Entities\User;
use App\Services\Repositories\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(User $user): bool
    {
        return $this->userRepository->register($user);
    }
}
