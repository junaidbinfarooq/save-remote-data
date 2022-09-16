<?php

namespace App\Entity;

use App\Repository\BankRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BankRepository::class)]
class Bank
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cardExpire = null;

    #[ORM\Column(length: 255)]
    private ?string $cardNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardExpire(): ?string
    {
        return $this->cardExpire;
    }

    public function setCardExpire(string $cardExpire): self
    {
        $this->cardExpire = $cardExpire;

        return $this;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber): self
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }
}
