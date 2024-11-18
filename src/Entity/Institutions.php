<?php

namespace App\Entity;

use App\Repository\InstitutionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstitutionsRepository::class)]
class Institutions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $university = null;

    #[ORM\Column(length: 255)]
    private ?string $reduction = null;

    #[ORM\OneToMany(targetEntity: Directors::class, mappedBy: 'Institut')]
    private Collection $directors;

    #[ORM\OneToMany(targetEntity: UserInstQwithLinkSubtitle::class, mappedBy: 'institute')]
    private Collection $userInstQwithLinkSubtitles;

    #[ORM\OneToMany(targetEntity: UserInstQoutLinkSubtitle::class, mappedBy: 'institute')]
    private Collection $userInstQoutLinkSubtitles;

    #[ORM\OneToMany(targetEntity: UserInstQoutLink::class, mappedBy: 'institute')]
    private Collection $userInstQoutLinks;

    public function __construct()
    {
        $this->directors = new ArrayCollection();
        $this->userInstQwithLinkSubtitles = new ArrayCollection();
        $this->userInstQoutLinkSubtitles = new ArrayCollection();
        $this->userInstQoutLinks = new ArrayCollection();
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

    public function getUniversity(): ?string
    {
        return $this->university;
    }

    public function setUniversity(string $university): static
    {
        $this->university = $university;

        return $this;
    }

    public function getReduction(): ?string
    {
        return $this->reduction;
    }

    public function setReduction(string $reduction): static
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * @return Collection<int, Directors>
     */
    public function getDirectors(): Collection
    {
        return $this->directors;
    }

    public function addDirector(Directors $director): static
    {
        if (!$this->directors->contains($director)) {
            $this->directors->add($director);
            $director->setInstitut($this);
        }

        return $this;
    }

    public function removeDirector(Directors $director): static
    {
        if ($this->directors->removeElement($director)) {
            // set the owning side to null (unless already changed)
            if ($director->getInstitut() === $this) {
                $director->setInstitut(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserInstQwithLinkSubtitle>
     */
    public function getUserInstQwithLinkSubtitles(): Collection
    {
        return $this->userInstQwithLinkSubtitles;
    }

    public function addUserInstQwithLinkSubtitle(UserInstQwithLinkSubtitle $userInstQwithLinkSubtitle): static
    {
        if (!$this->userInstQwithLinkSubtitles->contains($userInstQwithLinkSubtitle)) {
            $this->userInstQwithLinkSubtitles->add($userInstQwithLinkSubtitle);
            $userInstQwithLinkSubtitle->setInstitute($this);
        }

        return $this;
    }

    public function removeUserInstQwithLinkSubtitle(UserInstQwithLinkSubtitle $userInstQwithLinkSubtitle): static
    {
        if ($this->userInstQwithLinkSubtitles->removeElement($userInstQwithLinkSubtitle)) {
            // set the owning side to null (unless already changed)
            if ($userInstQwithLinkSubtitle->getInstitute() === $this) {
                $userInstQwithLinkSubtitle->setInstitute(null);
            }
        }

        return $this;
    }

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
            $userInstQoutLinkSubtitle->setInstitute($this);
        }

        return $this;
    }

    public function removeUserInstQoutLinkSubtitle(UserInstQoutLinkSubtitle $userInstQoutLinkSubtitle): static
    {
        if ($this->userInstQoutLinkSubtitles->removeElement($userInstQoutLinkSubtitle)) {
            // set the owning side to null (unless already changed)
            if ($userInstQoutLinkSubtitle->getInstitute() === $this) {
                $userInstQoutLinkSubtitle->setInstitute(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserInstQoutLink>
     */
    public function getUserInstQoutLinks(): Collection
    {
        return $this->userInstQoutLinks;
    }

    public function addUserInstQoutLink(UserInstQoutLink $userInstQoutLink): static
    {
        if (!$this->userInstQoutLinks->contains($userInstQoutLink)) {
            $this->userInstQoutLinks->add($userInstQoutLink);
            $userInstQoutLink->setInstitute($this);
        }

        return $this;
    }

    public function removeUserInstQoutLink(UserInstQoutLink $userInstQoutLink): static
    {
        if ($this->userInstQoutLinks->removeElement($userInstQoutLink)) {
            // set the owning side to null (unless already changed)
            if ($userInstQoutLink->getInstitute() === $this) {
                $userInstQoutLink->setInstitute(null);
            }
        }

        return $this;
    }
}
