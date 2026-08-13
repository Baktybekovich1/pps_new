<?php

namespace App\Entity;

use App\Repository\ResultsOfYearRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Tenant\OrganizationOwnedInterface;
use App\Tenant\OrganizationOwnedTrait;

#[ORM\Entity(repositoryClass: ResultsOfYearRepository::class)]
class ResultsOfYear implements OrganizationOwnedInterface
{
    use OrganizationOwnedTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'resultsOfYears')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Years $year = null;

    #[ORM\ManyToOne]
    private ?Teacher $teacher = null;

    #[ORM\Column(nullable: true)]
    private ?int $awardPoints = null;

    #[ORM\Column(nullable: true)]
    private ?int $researchPoints = null;

    #[ORM\Column(nullable: true)]
    private ?int $innovativePoints = null;

    #[ORM\Column(nullable: true)]
    private ?int $socialPoints = null;

    #[ORM\Column(nullable: true)]
    private ?int $sumPoints = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?Years
    {
        return $this->year;
    }

    public function setYear(?Years $year): static
    {
        $this->year = $year;

        return $this;
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

    public function getAwardPoints(): ?int
    {
        return $this->awardPoints;
    }

    public function setAwardPoints(?int $awardPoints): static
    {
        $this->awardPoints = $awardPoints;

        return $this;
    }

    public function getResearchPoints(): ?int
    {
        return $this->researchPoints;
    }

    public function setResearchPoints(?int $researchPoints): static
    {
        $this->researchPoints = $researchPoints;

        return $this;
    }

    public function getInnovativePoints(): ?int
    {
        return $this->innovativePoints;
    }

    public function setInnovativePoints(?int $innovativePoints): static
    {
        $this->innovativePoints = $innovativePoints;

        return $this;
    }

    public function getSocialPoints(): ?int
    {
        return $this->socialPoints;
    }

    public function setSocialPoints(?int $socialPoints): static
    {
        $this->socialPoints = $socialPoints;

        return $this;
    }

    public function getSumPoints(): ?int
    {
        return $this->sumPoints;
    }

    public function setSumPoints(?int $sumPoints): static
    {
        $this->sumPoints = $sumPoints;

        return $this;
    }
}
