<?php

namespace App\Entity;

use App\Repository\BankRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BankRepository::class)]
#[ORM\Table(name: 'banks')]
class Bank
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct(
        #[ORM\Column(name: 'card_expire', length: 5)]
        private string $cardExpire,

        #[ORM\Column(name: 'card_number', length: 16)]
        private string $cardNumber,

        #[ORM\Column(name: 'card_type', length: 10)]
        private string $cardType,

        #[ORM\Column(length: 10)]
        private string $currency,

        #[ORM\Column(length: 50)]
        private string $iban,
    )
    {
    }
}
