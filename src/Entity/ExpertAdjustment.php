<?php

namespace App\Entity;

use App\Repository\ExpertAdjustmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ExpertAdjustmentRepository::class)]
class ExpertAdjustment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['adjustment:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'expertAdjustments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['adjustment:read'])]
    private ?Expert $expert = null;

    #[ORM\ManyToOne(inversedBy: 'expertAdjustments')]
    #[Groups(['adjustment:read'])]
    private ?Teacher $targetTeacher = null;

    #[ORM\ManyToOne(inversedBy: 'expertAdjustments')]
    #[Groups(['adjustment:read'])]
    private ?Institute $targetInstitute = null;

    #[ORM\Column]
    #[Groups(['adjustment:read'])]
    private ?int $points = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['adjustment:read'])]
    private ?string $reason = null;

    #[ORM\Column]
    #[Groups(['adjustment:read'])]
    private bool $isActive = true;

    #[ORM\Column]
    #[Groups(['adjustment:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpert(): ?Expert
    {
        return $this->expert;
    }

    public function setExpert(?Expert $expert): static
    {
        $this->expert = $expert;

        return $this;
    }

    public function getTargetTeacher(): ?Teacher
    {
        return $this->targetTeacher;
    }

    public function setTargetTeacher(?Teacher $targetTeacher): static
    {
        $this->targetTeacher = $targetTeacher;

        return $this;
    }

    public function getTargetInstitute(): ?Institute
    {
        return $this->targetInstitute;
    }

    public function setTargetInstitute(?Institute $targetInstitute): static
    {
        $this->targetInstitute = $targetInstitute;

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

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
