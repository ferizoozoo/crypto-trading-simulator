<?php

namespace App\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "trades")]
class Trade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "buyer_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private int $buyerId;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "seller_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private int $sellerId;

    #[ORM\Column(type: "float")]
    private float $price;

    #[ORM\Column(type: "integer")]
    private int $amount;

    #[ORM\Column(type: "datetime")]
    private ?DateTime $time = null;

    public function __construct(
        int $buyerId,
        int $sellerId,
        float $price,
        int $amount,
        DateTime $time
    ) {
        $this->buyerId = $buyerId;
        $this->sellerId = $sellerId;
        $this->price = $price;
        $this->amount = $amount;
        $this->time = $time;
    }

    public function getBuyerId(): int
    {
        return $this->buyerId;
    }

    public function getSellerId(): int
    {
        return $this->sellerId;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getTime(): DateTime
    {
        return $this->time;
    }
}
