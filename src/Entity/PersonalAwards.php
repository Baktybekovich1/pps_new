<?php

namespace App\Entity;

use App\Repository\PersonalAwardsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonalAwardsRepository::class)]
class PersonalAwards
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: PersonalAwardsSubtitle::class, mappedBy: 'title',cascade: ['persist','remove'])]
    private Collection $personalAwardsSubtitles;

    public function __construct()
    {
        $this->personalAwardsSubtitles = new ArrayCollection();
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
     * @return Collection<int, PersonalAwardsSubtitle>
     */
    public function getPersonalAwardsSubtitles(): Collection
    {
        return $this->personalAwardsSubtitles;
    }

    public function addPersonalAwardsSubtitle(PersonalAwardsSubtitle $personalAwardsSubtitle): static
    {
        if (!$this->personalAwardsSubtitles->contains($personalAwardsSubtitle)) {
            $this->personalAwardsSubtitles->add($personalAwardsSubtitle);
            $personalAwardsSubtitle->setTitle($this);
        }

        return $this;
    }

    public function removePersonalAwardsSubtitle(PersonalAwardsSubtitle $personalAwardsSubtitle): static
    {
        if ($this->personalAwardsSubtitles->removeElement($personalAwardsSubtitle)) {
            // set the owning side to null (unless already changed)
            if ($personalAwardsSubtitle->getTitle() === $this) {
                $personalAwardsSubtitle->setTitle(null);
            }
        }

        return $this;
    }
}
