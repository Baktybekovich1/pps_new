<?php

namespace App\Entity;

use App\Repository\InstQwithLinkTitleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstQoutLinkTitleRepository::class)]
class InstQoutLinkTitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: InstQoutLinkSubtitle::class, mappedBy: 'award', cascade: ['persist', 'remove'])]
    private Collection $instQoutLinkSubtitles;

    public function __construct()
    {
        $this->instQoutLinkSubtitles = new ArrayCollection();
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
     * @return Collection<int, InstQwithLinkSubtitle>
     */
    public function getInstQoutLinkSubtitles(): Collection
    {
        return $this->instQoutLinkSubtitles;
    }

    public function setInstQoutLinkSubtitle(InstQoutLinkSubtitle $instQoutLinkSubtitle): static
    {
        if (!$this->instQoutLinkSubtitles->contains($instQoutLinkSubtitle)) {
            $this->instQoutLinkSubtitles->add($instQoutLinkSubtitle);
            $instQoutLinkSubtitle->setAward($this);
        }

        return $this;
    }

    public function removeInstQoutLinkSubtitle(InstQoutLinkSubtitle $instQoutLinkSubtitle): static
    {
        if ($this->instQoutLinkSubtitles->removeElement($instQoutLinkSubtitle)) {
            // set the owning side to null (unless already changed)
            if ($instQoutLinkSubtitle->getAward() === $this) {
                $instQoutLinkSubtitle->setAward(null);
            }
        }

        return $this;
    }

}
