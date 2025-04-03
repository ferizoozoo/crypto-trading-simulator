<?php

namespace App\Entities;

use App\Enums\OrderType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "orders")]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: "integer")]
    private int $amount;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private float $price;

    #[ORM\Column(type: "string", length: 8, enumType: OrderType::class)]
    private OrderType $type;

    #[ORM\Column(type: "integer")]
    private int $timestamp;

    public function __construct(
        ?User $user,
        int $amount,
        float $price,
        OrderType $type,
        int $timestamp
    ) {
        $this->user = $user;
        $this->amount = $amount;
        $this->price = $price;
        $this->type = $type;
        $this->timestamp = $timestamp;
    }

    public function getUserId(): int
    {
        return $this->user?->getId();
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getType(): OrderType
    {
        return $this->type;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
}
