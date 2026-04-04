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

    #[ORM\OneToOne(inversedBy: 'expert', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Teacher $teacher = null;

    #[ORM\OneToMany(targetEntity: ExpertAdjustment::class, mappedBy: 'expert', cascade: ['remove'], orphanRemoval: true)]
    private Collection $expertAdjustments;

    #[ORM\Column(length: 255)]
    private ?string $jobTitle = null;

    public function __construct()
    {
        $this->expertAdjustments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): static
    {
        $this->teacher = $teacher;

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


    /**
     * @return Collection<int, ExpertAdjustment>
     */
    public function getExpertAdjustments(): Collection
    {
        return $this->expertAdjustments;
    }

    public function addExpertAdjustment(ExpertAdjustment $expertAdjustment): static
    {
        if (!$this->expertAdjustments->contains($expertAdjustment)) {
            $this->expertAdjustments->add($expertAdjustment);
            $expertAdjustment->setExpert($this);
        }

        return $this;
    }

    public function removeExpertAdjustment(ExpertAdjustment $expertAdjustment): static
    {
        if ($this->expertAdjustments->removeElement($expertAdjustment)) {
            // set the owning side to null (unless already changed)
            if ($expertAdjustment->getExpert() === $this) {
                $expertAdjustment->setExpert(null);
            }
        }

        return $this;
    }
}
