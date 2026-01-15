<?php

namespace App\Entity;

use App\Repository\QuestionTitleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionTitleRepository::class)]
class QuestionTitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'questionTitles')]
    private ?Stage $stage = null;

    #[ORM\OneToMany(targetEntity: QuestionSubtitle::class, mappedBy: 'title')]
    private Collection $questionSubtitles;

    public function __construct()
    {
        $this->questionSubtitles = new ArrayCollection();
    }
    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStage(): ?Stage
    {
        return $this->stage;
    }

    public function setStage(?Stage $stage): static
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * @return Collection<int, QuestionSubtitle>
     */
    public function getQuestionSubtitles(): Collection
    {
        return $this->questionSubtitles;
    }

    public function addQuestionSubtitle(QuestionSubtitle $questionSubtitle): static
    {
        if (!$this->questionSubtitles->contains($questionSubtitle)) {
            $this->questionSubtitles->add($questionSubtitle);
            $questionSubtitle->setTitle($this);
        }

        return $this;
    }

    public function removeQuestionSubtitle(QuestionSubtitle $questionSubtitle): static
    {
        if ($this->questionSubtitles->removeElement($questionSubtitle)) {
            // set the owning side to null (unless already changed)
            if ($questionSubtitle->getTitle() === $this) {
                $questionSubtitle->setTitle(null);
            }
        }

        return $this;
    }
}
