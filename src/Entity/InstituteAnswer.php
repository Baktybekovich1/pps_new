<?php

namespace App\Entity;

use App\Repository\InstituteAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstituteAnswerRepository::class)]
class InstituteAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'instituteAnswers')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?Institute $institute = null;

    #[ORM\ManyToOne(inversedBy: 'instituteAnswers')]
    private ?InstituteQuestionSubtitle $subtitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $link = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\ManyToOne]
    private ?Years $academicYear = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstitute(): ?Institute
    {
        return $this->institute;
    }

    public function setInstitute(?Institute $institute): static
    {
        $this->institute = $institute;

        return $this;
    }

    public function getSubtitle(): ?InstituteQuestionSubtitle
    {
        return $this->subtitle;
    }

    public function setSubtitle(?InstituteQuestionSubtitle $subtitle): static
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

    public function getAcademicYear(): ?Years
    {
        return $this->academicYear;
    }

    public function setAcademicYear(?Years $academicYear): static
    {
        $this->academicYear = $academicYear;

        return $this;
    }
}
