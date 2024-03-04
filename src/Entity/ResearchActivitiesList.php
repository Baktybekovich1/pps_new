<?php

namespace App\Entity;

use App\Repository\ResearchActivitiesListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResearchActivitiesListRepository::class)]
class ResearchActivitiesList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: ResearchActivitiesSubtitle::class, mappedBy: 'category',cascade: ['persist','remove'] )]
    private Collection $researchActivitiesSubtitles;

    public function __construct()
    {
        $this->researchActivitiesSubtitles = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, ResearchActivitiesSubtitle>
     */
    public function getResearchActivitiesSubtitles(): Collection
    {
        return $this->researchActivitiesSubtitles;
    }

    public function addResearchActivitiesSubtitle(ResearchActivitiesSubtitle $researchActivitiesSubtitle): static
    {
        if (!$this->researchActivitiesSubtitles->contains($researchActivitiesSubtitle)) {
            $this->researchActivitiesSubtitles->add($researchActivitiesSubtitle);
            $researchActivitiesSubtitle->setCategory($this);
        }

        return $this;
    }

    public function removeResearchActivitiesSubtitle(ResearchActivitiesSubtitle $researchActivitiesSubtitle): static
    {
        if ($this->researchActivitiesSubtitles->removeElement($researchActivitiesSubtitle)) {
            // set the owning side to null (unless already changed)
            if ($researchActivitiesSubtitle->getCategory() === $this) {
                $researchActivitiesSubtitle->setCategory(null);
            }
        }

        return $this;
    }
}
