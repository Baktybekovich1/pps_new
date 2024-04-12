<?php

namespace App\Entity;

use App\Repository\UserPersonalAwardsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserPersonalAwardsRepository::class)]
class UserPersonalAwards
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: PersonalAwardsSubtitle::class)]
    private ?PersonalAwardsSubtitle $subtitle;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $link = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

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


    public function getSubtitle(): ?PersonalAwardsSubtitle
    {
        return $this->subtitle;
    }


    public function setSubtitle(?PersonalAwardsSubtitle $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    public function getPoints():int
    {
        return $this->subtitle->getPoints();
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }


}
