<?php

namespace App\Entity;

use App\Repository\TeacherAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherAnswerRepository::class)]
class TeacherAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'teacherAnswers')]
    private ?Teacher $teacher = null;

    #[ORM\ManyToOne(inversedBy: 'teacherAnswers')]
    private ?QuestionSubtitle $subtitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $link = null;

    #[ORM\Column]
    private ?bool $active = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSubtitle(): ?QuestionSubtitle
    {
        return $this->subtitle;
    }

    public function setSubtitle(?QuestionSubtitle $subtitle): static
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
