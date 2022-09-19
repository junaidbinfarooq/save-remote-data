<?php

namespace App\Entity;

use App\Repository\HairRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HairRepository::class)]
#[ORM\Table(name: 'hairs')]
class Hair
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'hair', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function __construct(
        #[Assert\NotBlank]
        #[ORM\Column(length: 20)]
        private ?string $color = null,

        #[Assert\NotBlank]
        #[ORM\Column(length: 20)]
        private ?string $type = null,
    )
    {
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
