<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InstitutionQuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstitutionQuestionRepository::class)]
#[ApiResource]
class InstitutionQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToMany(targetEntity: InstitutionQuestionOption::class, mappedBy: 'question')]
    private Collection $institutionQuestionOptions;

    public function __construct()
    {
        $this->institutionQuestionOptions = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, InstitutionQuestionOption>
     */
    public function getInstitutionQuestionOptions(): Collection
    {
        return $this->institutionQuestionOptions;
    }

    public function addInstitutionQuestionOption(InstitutionQuestionOption $institutionQuestionOption): static
    {
        if (!$this->institutionQuestionOptions->contains($institutionQuestionOption)) {
            $this->institutionQuestionOptions->add($institutionQuestionOption);
            $institutionQuestionOption->setQuestion($this);
        }

        return $this;
    }

    public function removeInstitutionQuestionOption(InstitutionQuestionOption $institutionQuestionOption): static
    {
        if ($this->institutionQuestionOptions->removeElement($institutionQuestionOption)) {
            // set the owning side to null (unless already changed)
            if ($institutionQuestionOption->getQuestion() === $this) {
                $institutionQuestionOption->setQuestion(null);
            }
        }

        return $this;
    }
}
