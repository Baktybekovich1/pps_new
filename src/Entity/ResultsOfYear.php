<?php

namespace App\Entity;

use App\Repository\ResultsOfYearRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultsOfYearRepository::class)]
class ResultsOfYear
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'resultsOfYears')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Years $year = null;

    #[ORM\ManyToOne(inversedBy: 'resultsOfYears')]
    private ?User $account = null;

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

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(?User $account): static
    {
        $this->account = $account;

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
