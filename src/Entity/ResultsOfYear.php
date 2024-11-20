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
    private ?int $points = null;

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
