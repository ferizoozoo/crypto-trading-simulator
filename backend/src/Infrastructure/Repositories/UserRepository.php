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

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $entityManager;
    }

    public function add(User $user): bool
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findById(int $id): ?User
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function update(User $user): bool
    {
        $this->entityManager->flush();
        return true;
    }

    public function deleteByEmail(string $email): bool
    {
        $user = $this->findByEmail($email);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return true;
    }
}
