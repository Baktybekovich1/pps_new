<?php

namespace App\Entity;

use App\Repository\InstQwithLinkSubtitleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstQwithLinkSubtitleRepository::class)]
class InstQoutLinkSubtitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $points = null;

    #[ORM\ManyToOne(inversedBy: 'instQoutLinkSubtitles')]
    private ?InstQoutLinkTitle $award = null;

    #[ORM\OneToMany(targetEntity: UserInstQoutLinkSubtitle::class, mappedBy: 'award')]
    private Collection $userInstQoutLinkSubtitles;

    public function __construct()
    {
        $this->userInstQoutLinkSubtitles = new ArrayCollection();
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

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getAward(): ?InstQoutLinkTitle
    {
        return $this->award;
    }

    public function setAward(?InstQoutLinkTitle $award): void
    {
        $this->award = $award;
    }

    /**
     * @return Collection<int, instQoutLinkTitle>
     */

    /**
     * @return Collection<int, UserInstQoutLinkSubtitle>
     */
    public function getUserInstQoutLinkSubtitles(): Collection
    {
        return $this->userInstQoutLinkSubtitles;
    }

    public function addUserInstQoutLinkSubtitle(UserInstQoutLinkSubtitle $userInstQoutLinkSubtitle): static
    {
        if (!$this->userInstQoutLinkSubtitles->contains($userInstQoutLinkSubtitle)) {
            $this->userInstQoutLinkSubtitles->add($userInstQoutLinkSubtitle);
            $userInstQoutLinkSubtitle->setAward($this);
        }

        return $this;
    }

    public function removeUserInstQoutLinkSubtitle(UserInstQoutLinkSubtitle $userInstQoutLinkSubtitle): static
    {
        if ($this->userInstQoutLinkSubtitles->removeElement($userInstQoutLinkSubtitle)) {
            // set the owning side to null (unless already changed)
            if ($userInstQoutLinkSubtitle->getAward() === $this) {
                $userInstQoutLinkSubtitle->setAward(null);
            }
        }

        return $this;
    }


}
