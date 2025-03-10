<?php

namespace App\Infrastructure\Repositories;

use App\Entities\User;
use App\Services\Repositories\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function register(User $user): bool
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }
}
