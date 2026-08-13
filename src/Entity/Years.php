<?php

namespace App\Entity;

use App\Repository\YearsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Tenant\OrganizationOwnedInterface;
use App\Tenant\OrganizationOwnedTrait;

#[ORM\Entity(repositoryClass: YearsRepository::class)]
class Years implements OrganizationOwnedInterface
{
    use OrganizationOwnedTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true, options: ['default' => false])]
    private ?bool $isCurrent = false;

    #[ORM\Column(nullable: true, options: ['default' => false])]
    private ?bool $isLocked = false;

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

    public function isCurrent(): ?bool
    {
        return $this->isCurrent;
    }

    public function setIsCurrent(?bool $isCurrent): static
    {
        $this->isCurrent = $isCurrent;

        return $this;
    }

    public function isLocked(): ?bool
    {
        return $this->isLocked;
    }

    public function setIsLocked(?bool $isLocked): static
    {
        $this->isLocked = $isLocked;

        return $this;
    }
}
