<?php

namespace App\Entity;

use App\Repository\PositionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PositionRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Такая должность уже есть')]
class Position
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['position:read','teacher:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['position:read','teacher:read'])]
    private string $name;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['position:read','teacher:read'])]
    private ?string $reduction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getReduction(): ?string
    {
        return $this->reduction;
    }

    public function setReduction(?string $r): self
    {
        $this->reduction = $r;
        return $this;
    }
}
