<?php

namespace App\Entity;

use App\Repository\InstQwithLinkTitleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstQwithLinkTitleRepository::class)]
class InstQwithLinkTitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: InstQwithLinkSubtitle::class, mappedBy: 'award', cascade: ['persist', 'remove'])]
    private Collection $instQwithLinkSubtitles;

    public function __construct()
    {
        $this->instQwithLinkSubtitles = new ArrayCollection();
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
    public function getInstQwithLinkSubtitles(): Collection
    {
        return $this->instQwithLinkSubtitles;
    }

    public function setInstQwithLinkSubtitle(InstQwithLinkSubtitle $instQwithLinkSubtitle): static
    {
        if (!$this->instQwithLinkSubtitles->contains($instQwithLinkSubtitle)) {
            $this->instQwithLinkSubtitles->add($instQwithLinkSubtitle);
            $instQwithLinkSubtitle->setAward($this);
        }

        return $this;
    }

    public function removeInstQwithLinkSubtitle(InstQwithLinkSubtitle $instQwithLinkSubtitle): static
    {
        if ($this->instQwithLinkSubtitles->removeElement($instQwithLinkSubtitle)) {
            // set the owning side to null (unless already changed)
            if ($instQwithLinkSubtitle->getAward() === $this) {
                $instQwithLinkSubtitle->setAward(null);
            }
        }

        return $this;
    }

}
