<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InstitutionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstitutionsRepository::class)]
#[ApiResource]
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

    #[ORM\Column(nullable: true)]
    private ?int $totalTeachers = null;

    #[ORM\OneToMany(targetEntity: Director::class, mappedBy: 'institutions', cascade: ['persist', 'remove'])]
    private Collection $director;

    #[ORM\OneToMany(targetEntity: InstitutionAnswer::class, mappedBy: 'institution', orphanRemoval: true)]
    private Collection $institutionAnswers;

    public function __construct()
    {
        $this->director = new ArrayCollection();
        $this->institutionAnswers = new ArrayCollection();
    }


    public function __toString(): string
    {
        return $this->name ?? 'Unnamed Institute';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalTeachers(): ?int
    {
        return $this->totalTeachers;
    }

    public function setTotalTeachers(?int $totalTeachers): void
    {
        $this->totalTeachers = $totalTeachers;
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
     * @return Collection<int, InstitutionAnswer>
     */
    public function getInstitutionAnswers(): Collection
    {
        return $this->institutionAnswers;
    }

    public function addInstitutionAnswer(InstitutionAnswer $institutionAnswer): static
    {
        if (!$this->institutionAnswers->contains($institutionAnswer)) {
            $this->institutionAnswers->add($institutionAnswer);
            $institutionAnswer->setInstitution($this);
        }

        return $this;
    }

    public function removeInstitutionAnswer(InstitutionAnswer $institutionAnswer): static
    {
        if ($this->institutionAnswers->removeElement($institutionAnswer)) {
            // set the owning side to null (unless already changed)
            if ($institutionAnswer->getInstitution() === $this) {
                $institutionAnswer->setInstitution(null);
            }
        }

        return $this;
    }


}
