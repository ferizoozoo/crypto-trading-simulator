<?php

namespace App\Infrastructure\Repositories;

use App\Entities\Order;
use App\Entities\User;
use App\Services\Repositories\OrderRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

// TODO: probably these methods will be repeated, so create a RepositoryInterface
class OrderRepository extends ServiceEntityRepository implements OrderRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Order::class);
        $this->entityManager = $entityManager;
    }

    public function add(Order $order): ?Order
    {
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

    public function findById(int $id): ?Order
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function delete(int $id): bool
    {
        $user = $this->findById($id);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return true;
    }
}
