<?php

namespace App\Entity;

use App\Repository\InstituteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: InstituteRepository::class)]
class Institute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['institute:read','teacher:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['institute:read','teacher:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'institutes')]
    private ?Organization $organization = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['institute:read','teacher:read'])]
    private ?string $reduction = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $teacherTotal = null;


    public function __toString(): string
    {
        return $this->getName();
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

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): static
    {
        $this->organization = $organization;

        return $this;
    }

    public function getReduction(): ?string
    {
        return $this->reduction;
    }

    public function setReduction(?string $reduction): static
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function getTeacherTotal(): ?int
    {
        return $this->teacherTotal;
    }

    public function setTeacherTotal(?int $v): self
    {
        $this->teacherTotal = $v;
        return $this;
    }
}
