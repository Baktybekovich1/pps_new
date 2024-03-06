<?php

namespace App\Entity;

use App\Repository\AwardsAndLinkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AwardsAndLinkRepository::class)]
class AwardsAndLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nameId = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $link = null;

    #[ORM\ManyToOne(inversedBy: 'stateAwards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserProgress $userProgress = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameId(): ?int
    {
        return $this->nameId;
    }

    public function setNameId(?int $nameId): void
    {
        $this->nameId = $nameId;
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

    public function getUserProgress(): ?UserProgress
    {
        return $this->userProgress;
    }

    public function setUserProgress(?UserProgress $userProgress): static
    {
        $this->userProgress = $userProgress;

        return $this;
    }
}
