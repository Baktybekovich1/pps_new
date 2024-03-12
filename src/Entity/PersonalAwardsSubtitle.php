<?php

namespace App\Entity;

use App\Repository\PersonalAwardsSubtitleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: PersonalAwardsSubtitleRepository::class)]
class PersonalAwardsSubtitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $points = null;

    #[ORM\ManyToOne(inversedBy: 'personalAwardsSubtitles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?PersonalAwards $title = null;

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

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getTitle(): ?PersonalAwards
    {
        return $this->title;
    }

    public function setTitle(?PersonalAwards $title): static
    {
        $this->title = $title;

        return $this;
    }
}
