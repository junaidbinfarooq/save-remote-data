<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: 'posts')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct(
        #[Assert\NotBlank]
        #[ORM\Column(length: 255)]
        private string $title,

        #[ORM\Column(type: Types::TEXT)]
        private ?string $body = null,

        #[ORM\Column(type: Types::INTEGER, nullable: true)]
        private ?int $reactions = 0,
    )
    {
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}
