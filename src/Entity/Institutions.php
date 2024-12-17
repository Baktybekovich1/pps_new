<?php

namespace App\Entity;

use App\Repository\InstitutionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstitutionsRepository::class)]
class Institutions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $university = null;

    #[ORM\Column(length: 255)]
    private ?string $reduction = null;

    #[ORM\OneToMany(targetEntity: Directors::class, mappedBy: 'Institut')]
    private Collection $directors;


    public function __construct()
    {
        $this->directors = new ArrayCollection();
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

    public function getUniversity(): ?string
    {
        return $this->university;
    }

    public function setUniversity(string $university): static
    {
        $this->university = $university;

        return $this;
    }

    public function getReduction(): ?string
    {
        return $this->reduction;
    }

    public function setReduction(string $reduction): static
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * @return Collection<int, Directors>
     */
    public function getDirectors(): Collection
    {
        return $this->directors;
    }

    public function addDirector(Directors $director): static
    {
        if (!$this->directors->contains($director)) {
            $this->directors->add($director);
            $director->setInstitut($this);
        }

        return $this;
    }

    public function removeDirector(Directors $director): static
    {
        if ($this->directors->removeElement($director)) {
            // set the owning side to null (unless already changed)
            if ($director->getInstitut() === $this) {
                $director->setInstitut(null);
            }
        }

        return $this;
    }


}
