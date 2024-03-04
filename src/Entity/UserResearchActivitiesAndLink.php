<?php

namespace App\Entity;

use App\Repository\UserResearchActivitiesAndLinkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserResearchActivitiesAndLinkRepository::class)]
class UserResearchActivitiesAndLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $link = null;

    #[ORM\ManyToOne(inversedBy: 'userResearchActivitiesAndLinks')]
    private ?UserResearchActivities $ural = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

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

    public function getUral(): ?UserResearchActivities
    {
        return $this->ural;
    }

    public function setUral(?UserResearchActivities $ural): static
    {
        $this->ural = $ural;

        return $this;
    }
}
