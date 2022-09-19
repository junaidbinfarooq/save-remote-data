<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: 'addresses')]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    public function __construct(
        #[Assert\NotBlank]
        #[ORM\Column(length: 255)]
        private string $address,

        #[Assert\NotBlank]
        #[ORM\Column(length: 20)]
        private string $city,

        #[Assert\NotBlank]
        #[ORM\Column(name: 'postal_code', length: 6)]
        private string $postalCode,

        #[Assert\NotBlank]
        #[ORM\Column(length: 20)]
        private string $state,
    )
    {
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
