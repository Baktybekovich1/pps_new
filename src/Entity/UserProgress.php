<?php

namespace App\Entity;

use App\Repository\UserProgressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserProgressRepository::class)]
class UserProgress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $degree = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rank = null;

    #[ORM\OneToMany(targetEntity: AwardsAndLink::class, mappedBy: 'userProgress', cascade: ['persist', 'remove'])]
    private Collection $stateAwards;

    #[ORM\Column(nullable: true)]
    private ?int $points = null;

    public function __construct()
    {
        $this->stateAwards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDegree(): ?string
    {
        return $this->degree;
    }

    public function setDegree(?string $degree): static
    {
        $this->degree = $degree;

        return $this;
    }

    public function getRank(): ?string
    {
        return $this->rank;
    }

    public function setRank(?string $rank): static
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * @return Collection<int, AwardsAndLink>
     */
    public function getStateAwards(): Collection
    {
        return $this->stateAwards;
    }

    public function addStateAward(AwardsAndLink $stateAward): static
    {
        if (!$this->stateAwards->contains($stateAward)) {
            $this->stateAwards->add($stateAward);
            $stateAward->setUserProgress($this);
        }

        return $this;
    }

    public function removeStateAward(AwardsAndLink $stateAward): static
    {
        if ($this->stateAwards->removeElement($stateAward)) {
            // set the owning side to null (unless already changed)
            if ($stateAward->getUserProgress() === $this) {
                $stateAward->setUserProgress(null);
            }
        }

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
}
