<?php

namespace App\Entity;

use App\Repository\BankRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: 'cardNumber', message: 'No two banks could have the same card number')]
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
        #[Assert\NotBlank]
        #[ORM\Column(name: 'card_expire', length: 5)]
        private string $cardExpire,

        #[Assert\NotBlank]
        #[ORM\Column(name: 'card_number', length: 16, unique: true)]
        private string $cardNumber,

        #[Assert\NotBlank]
        #[ORM\Column(name: 'card_type', length: 10)]
        private string $cardType,

        #[Assert\NotBlank]
        #[ORM\Column(length: 10)]
        private string $currency,

        #[Assert\NotBlank]
        #[ORM\Column(length: 50)]
        private string $iban,
    )
    {
    }
}
