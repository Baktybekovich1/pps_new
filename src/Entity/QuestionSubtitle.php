<?php

namespace App\Entity;

use App\Repository\QuestionSubtitleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionSubtitleRepository::class)]
class QuestionSubtitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'questionSubtitles')]
    private ?QuestionTitle $title = null;

    #[ORM\Column]
    private ?int $point = null;

    #[ORM\OneToMany(targetEntity: TeacherAnswer::class, mappedBy: 'subtitle')]
    private Collection $teacherAnswers;

    public function __construct()
    {
        $this->teacherAnswers = new ArrayCollection();
    }
    public function __toString(): string
    {
        return $this->title .' ' . $this->name ?? '';
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

    public function getTitle(): ?QuestionTitle
    {
        return $this->title;
    }

    public function setTitle(?QuestionTitle $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPoint(): ?int
    {
        return $this->point;
    }

    public function setPoint(int $point): static
    {
        $this->point = $point;

        return $this;
    }

    /**
     * @return Collection<int, TeacherAnswer>
     */
    public function getTeacherAnswers(): Collection
    {
        return $this->teacherAnswers;
    }

    public function addTeacherAnswer(TeacherAnswer $teacherAnswer): static
    {
        if (!$this->teacherAnswers->contains($teacherAnswer)) {
            $this->teacherAnswers->add($teacherAnswer);
            $teacherAnswer->setSubtitle($this);
        }

        return $this;
    }

    public function removeTeacherAnswer(TeacherAnswer $teacherAnswer): static
    {
        if ($this->teacherAnswers->removeElement($teacherAnswer)) {
            // set the owning side to null (unless already changed)
            if ($teacherAnswer->getSubtitle() === $this) {
                $teacherAnswer->setSubtitle(null);
            }
        }

        return $this;
    }
}
