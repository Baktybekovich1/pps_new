<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ExpertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpertRepository::class)]
#[ApiResource]
class Expert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'expert', cascade: ['persist', 'remove'])]
    private ?User $account = null;

    #[ORM\Column(length: 255)]
    private ?string $jobTitle = null;

    #[ORM\OneToMany(targetEntity: UserExpertPoint::class, mappedBy: 'expert')]
    private Collection $userExpertPoints;

    public function __construct()
    {
        $this->userExpertPoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(?User $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(string $jobTitle): static
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }


    public function getUserExpertPoints(): Collection
    {
        return $this->userExpertPoints;
    }

    public function addUserExpertPoint(UserExpertPoint $userExpertPoint): static
    {
        if (!$this->userExpertPoints->contains($userExpertPoint)) {
            $this->userExpertPoints->add($userExpertPoint);
            $userExpertPoint->setExpert($this);
        }

        return $this;
    }

    public function removeUserExpertPoint(UserExpertPoint $userExpertPoint): static
    {
        if ($this->userExpertPoints->removeElement($userExpertPoint)) {
            // set the owning side to null (unless already changed)
            if ($userExpertPoint->getExpert() === $this) {
                $userExpertPoint->setExpert(null);
            }
        }

        return $this;
    }
}
