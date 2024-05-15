<?php

namespace App\Entity;

use App\Repository\UserInfoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserInfoRepository::class)]
class UserInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Institutions::class)]
    private ?Institutions $institutions = null;

    #[ORM\ManyToOne(targetEntity: Position::class)]
    private ?Position $position = null;

    #[ORM\Column(length: 255)]
    private ?string $regular = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }


    public function setUser(?User $user): void
    {
        $this->user = $user;
    }


    public function getInstitutions(): ?Institutions
    {
        return $this->institutions;
    }


    public function setInstitutions(?Institutions $institutions): void
    {
        $this->institutions = $institutions;
    }



    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getRegular(): ?string
    {
        return $this->regular;
    }

    public function setRegular(string $regular): static
    {
        $this->regular = $regular;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

}
