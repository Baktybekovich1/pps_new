<?php

namespace App\Entity;

use App\Repository\InnovativeEducationListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InnovativeEducationListRepository::class)]
class InnovativeEducationList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: InnovativeEducationSubtitle::class, mappedBy: 'title',cascade: ['persist','remove'])]
    private Collection $innovativeEducationSubtitles;

    public function __construct()
    {
        $this->innovativeEducationSubtitles = new ArrayCollection();
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
     * @return Collection<int, InnovativeEducationSubtitle>
     */
    public function getInnovativeEducationSubtitles(): Collection
    {
        return $this->innovativeEducationSubtitles;
    }

    public function addInnovativeEducationSubtitle(InnovativeEducationSubtitle $innovativeEducationSubtitle): static
    {
        if (!$this->innovativeEducationSubtitles->contains($innovativeEducationSubtitle)) {
            $this->innovativeEducationSubtitles->add($innovativeEducationSubtitle);
            $innovativeEducationSubtitle->setTitle($this);
        }

        return $this;
    }

    public function removeInnovativeEducationSubtitle(InnovativeEducationSubtitle $innovativeEducationSubtitle): static
    {
        if ($this->innovativeEducationSubtitles->removeElement($innovativeEducationSubtitle)) {
            // set the owning side to null (unless already changed)
            if ($innovativeEducationSubtitle->getTitle() === $this) {
                $innovativeEducationSubtitle->setTitle(null);
            }
        }

        return $this;
    }
}
