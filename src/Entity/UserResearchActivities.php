<?php

namespace App\Entity;

use App\Repository\UserResearchActivitiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserResearchActivitiesRepository::class)]
class UserResearchActivities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column(nullable: true)]
    private ?int $points = null;

    #[ORM\OneToMany(targetEntity: UserResearchActivitiesAndLink::class, mappedBy: 'ural',cascade: ['persist', 'remove'])]
    private Collection $userResearchActivitiesAndLinks;

    public function __construct()
    {
        $this->userResearchActivitiesAndLinks = new ArrayCollection();
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

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): static
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return Collection<int, UserResearchActivitiesAndLink>
     */
    public function getUserResearchActivitiesAndLinks(): Collection
    {
        return $this->userResearchActivitiesAndLinks;
    }

    public function addUserResearchActivitiesAndLink(UserResearchActivitiesAndLink $userResearchActivitiesAndLink): static
    {
        if (!$this->userResearchActivitiesAndLinks->contains($userResearchActivitiesAndLink)) {
            $this->userResearchActivitiesAndLinks->add($userResearchActivitiesAndLink);
            $userResearchActivitiesAndLink->setUral($this);
        }

        return $this;
    }

    public function removeUserResearchActivitiesAndLink(UserResearchActivitiesAndLink $userResearchActivitiesAndLink): static
    {
        if ($this->userResearchActivitiesAndLinks->removeElement($userResearchActivitiesAndLink)) {
            // set the owning side to null (unless already changed)
            if ($userResearchActivitiesAndLink->getUral() === $this) {
                $userResearchActivitiesAndLink->setUral(null);
            }
        }

        return $this;
    }
}
