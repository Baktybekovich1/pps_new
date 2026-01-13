<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Организация с таким названием уже есть')]
class Organization
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['organization:read','teacher:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['organization:read','teacher:read'])]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: Institute::class, orphanRemoval: true)]
    private Collection $institutes;

    public function __construct()
    {
        $this->institutes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }
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


    public function getInstitutes(): Collection
    {
        return $this->institutes;
    }
}
