<?php

namespace App\Entity;

use App\Repository\UserResearchActivitiesListRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserResearchActivitiesListRepository::class)]
class UserResearchActivitiesList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column]
    private ?int $ralId = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $link = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getRalId(): ?int
    {
        return $this->ralId;
    }

    public function setRalId(int $ralId): static
    {
        $this->ralId = $ralId;

        return $this;
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
}
