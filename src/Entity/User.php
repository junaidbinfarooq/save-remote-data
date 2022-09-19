<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['firstName', 'lastName'], message: 'The combination of first name and last name cannot be duplicate')]
#[UniqueEntity(fields: 'username', message: 'No two users could have the same username')]
#[UniqueEntity(fields: 'email', message: 'No two users could have the same email')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Bank::class)]
    private Collection $banks;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Address::class, orphanRemoval: true)]
    private Collection $addresses;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class, orphanRemoval: true)]
    private Collection $posts;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Hair $hair = null;

    public function __construct(
        #[Assert\NotBlank]
        #[ORM\Column(name: 'first_name', length: 255)]
        private string $firstName,

        #[Assert\NotBlank]
        #[ORM\Column(name: 'last_name', length: 255)]
        private string $lastName,

        #[Assert\NotBlank]
        #[ORM\Column(length: 50, unique: true)]
        private string $username,

        #[Assert\NotBlank]
        #[ORM\Column(length: 20, unique: true)]
        private string $email,

        #[ORM\Column(length: 20)]
        private ?string $phone = null,

        #[ORM\Column(name: 'birth_date', type: Types::DATETIME_MUTABLE)]
        private ?\DateTimeInterface $birthDate = null,

        #[ORM\Column(type: Types::INTEGER)]
        private ?float $height = null,

        #[ORM\Column(type: Types::INTEGER)]
        private ?float $weight = null,
    )
    {
        $this->banks = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function addBank(Bank $bank): self
    {
        if (!$this->banks->contains($bank)) {
            $this->banks->add($bank);
        }

        return $this;
    }

    public function addAddress(Address $address): void
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setUser($this);
        }
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);

            $post->setUser($this);
        }

        return $this;
    }

    public function setHair(?Hair $hair): void
    {
        if ($hair === null && $this->hair !== null) {
            $this->hair->setUser(null);
        }

        if ($hair !== null && $hair->getUser() !== $this) {
            $hair->setUser($this);
        }

        $this->hair = $hair;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
