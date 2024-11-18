<?php

namespace App\Entity;

use App\Repository\DirectorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DirectorsRepository::class)]
class Directors
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'directors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'directors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Institutions $Institut = null;

    #[ORM\OneToMany(targetEntity: UserInstQwithLink::class, mappedBy: 'director')]
    private Collection $userInstQwithLinks;

    public function __construct()
    {
        $this->userInstQwithLinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }



    public function getInstitut(): ?Institutions
    {
        return $this->Institut;
    }

    public function setInstitut(?Institutions $Institut): static
    {
        $this->Institut = $Institut;

        return $this;
    }

    /**
     * @return Collection<int, UserInstQwithLink>
     */
    public function getUserInstQwithLinks(): Collection
    {
        return $this->userInstQwithLinks;
    }

    public function addUserInstQwithLink(UserInstQwithLink $userInstQwithLink): static
    {
        if (!$this->userInstQwithLinks->contains($userInstQwithLink)) {
            $this->userInstQwithLinks->add($userInstQwithLink);
            $userInstQwithLink->setDirector($this);
        }

        return $this;
    }

    public function removeUserInstQwithLink(UserInstQwithLink $userInstQwithLink): static
    {
        if ($this->userInstQwithLinks->removeElement($userInstQwithLink)) {
            // set the owning side to null (unless already changed)
            if ($userInstQwithLink->getDirector() === $this) {
                $userInstQwithLink->setDirector(null);
            }
        }

        return $this;
    }
}
