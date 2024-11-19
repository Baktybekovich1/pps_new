<?php

namespace App\Entity;

use App\Repository\SocialActivitiesListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SocialActivitiesListRepository::class)]
class SocialActivitiesList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: SocialActivitiesSubtitle::class, mappedBy: 'title',cascade: ['persist','remove'])]
    private Collection $socialActivitiesSubtitles;

    public function __construct()
    {
        $this->socialActivitiesSubtitles = new ArrayCollection();
    }
    public function __toString(): string
    {
        return $this->name ?? 'Unnamed Award';
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
     * @return Collection<int, SocialActivitiesSubtitle>
     */
    public function getSocialActivitiesSubtitles(): Collection
    {
        return $this->socialActivitiesSubtitles;
    }

    public function addSocialActivitiesSubtitle(SocialActivitiesSubtitle $socialActivitiesSubtitle): static
    {
        if (!$this->socialActivitiesSubtitles->contains($socialActivitiesSubtitle)) {
            $this->socialActivitiesSubtitles->add($socialActivitiesSubtitle);
            $socialActivitiesSubtitle->setTitle($this);
        }

        return $this;
    }

    public function removeSocialActivitiesSubtitle(SocialActivitiesSubtitle $socialActivitiesSubtitle): static
    {
        if ($this->socialActivitiesSubtitles->removeElement($socialActivitiesSubtitle)) {
            // set the owning side to null (unless already changed)
            if ($socialActivitiesSubtitle->getTitle() === $this) {
                $socialActivitiesSubtitle->setTitle(null);
            }
        }

        return $this;
    }
}
