<?php

namespace App\Entity;

use App\Repository\InstituteQuestionTitleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstituteQuestionTitleRepository::class)]
class InstituteQuestionTitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\OneToMany(targetEntity: InstituteQuestionSubtitle::class, mappedBy: 'title')]
    private Collection $subtitle;

    public function __construct()
    {
        $this->subtitle = new ArrayCollection();
    }
    public function __toString(): string
    {
        return $this->name ?? '';
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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, InstituteQuestionSubtitle>
     */
    public function getSubtitle(): Collection
    {
        return $this->subtitle;
    }

    public function addSubtitle(InstituteQuestionSubtitle $subtitle): static
    {
        if (!$this->subtitle->contains($subtitle)) {
            $this->subtitle->add($subtitle);
            $subtitle->setTitle($this);
        }

        return $this;
    }

    public function removeSubtitle(InstituteQuestionSubtitle $subtitle): static
    {
        if ($this->subtitle->removeElement($subtitle)) {
            // set the owning side to null (unless already changed)
            if ($subtitle->getTitle() === $this) {
                $subtitle->setTitle(null);
            }
        }

        return $this;
    }
}
