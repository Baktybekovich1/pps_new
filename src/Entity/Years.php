<?php

namespace App\Entity;

use App\Repository\YearsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: YearsRepository::class)]
class Years
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: ResultsOfYear::class, mappedBy: 'year')]
    private Collection $resultsOfYears;

    public function __construct()
    {
        $this->resultsOfYears = new ArrayCollection();
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

    /**
     * @return Collection<int, ResultsOfYear>
     */
    public function getResultsOfYears(): Collection
    {
        return $this->resultsOfYears;
    }

    public function addResultsOfYear(ResultsOfYear $resultsOfYear): static
    {
        if (!$this->resultsOfYears->contains($resultsOfYear)) {
            $this->resultsOfYears->add($resultsOfYear);
            $resultsOfYear->setYear($this);
        }

        return $this;
    }

    public function removeResultsOfYear(ResultsOfYear $resultsOfYear): static
    {
        if ($this->resultsOfYears->removeElement($resultsOfYear)) {
            // set the owning side to null (unless already changed)
            if ($resultsOfYear->getYear() === $this) {
                $resultsOfYear->setYear(null);
            }
        }

        return $this;
    }
}
