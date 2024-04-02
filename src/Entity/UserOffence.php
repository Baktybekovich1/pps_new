<?php

namespace App\Entity;

use App\Repository\UserOffenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserOffenceRepository::class)]
class UserOffence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: OffenceList::class)]
    private ?OffenceList $offenceList;

    #[ORM\Column]
    private ?int $quantity = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }


    public function getOffenceList(): ?OffenceList
    {
        return $this->offenceList;
    }


    public function setOffenceList(?OffenceList $offenceList): void
    {
        $this->offenceList = $offenceList;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
