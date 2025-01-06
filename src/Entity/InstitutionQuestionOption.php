<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InstitutionQuestionOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstitutionQuestionOptionRepository::class)]
#[ApiResource]
class InstitutionQuestionOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'institutionQuestionOptions')]
    private ?InstitutionQuestion $question = null;

    #[ORM\Column]
    private ?bool $option = null;

    #[ORM\Column]
    private ?bool $link = null;

    #[orm\Column]
    private ?int $points = null;

    #[ORM\OneToMany(targetEntity: InstitutionAnswer::class, mappedBy: 'question')]
    private Collection $institutionAnswers;

    public function __construct()
    {
        $this->institutionAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getQuestion(): ?InstitutionQuestion
    {
        return $this->question;
    }

    public function setQuestion(?InstitutionQuestion $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function isOption(): ?bool
    {
        return $this->option;
    }

    public function setOption(bool $option): static
    {
        $this->option = $option;

        return $this;
    }

    public function isLink(): ?bool
    {
        return $this->link;
    }

    public function setLink(bool $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;
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
            $institutionAnswer->setQuestion($this);
        }

        return $this;
    }

    public function removeInstitutionAnswer(InstitutionAnswer $institutionAnswer): static
    {
        if ($this->institutionAnswers->removeElement($institutionAnswer)) {
            // set the owning side to null (unless already changed)
            if ($institutionAnswer->getQuestion() === $this) {
                $institutionAnswer->setQuestion(null);
            }
        }

        return $this;
    }
}
