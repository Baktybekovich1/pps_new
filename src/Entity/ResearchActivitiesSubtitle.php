<?php

namespace App\Entity;

use App\Repository\ResearchActivitiesSubtitleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: ResearchActivitiesSubtitleRepository::class)]
class ResearchActivitiesSubtitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255 ,nullable: true)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $points = null;



    #[ORM\ManyToOne(inversedBy: 'researchActivitiesSubtitles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?ResearchActivitiesList $category = null;

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


    public function getCategory(): ?ResearchActivitiesList
    {
        return $this->category;
    }

    public function setCategory(?ResearchActivitiesList $category): static
    {
        $this->category = $category;

        return $this;
    }
}
