<?php

namespace App\Entity;

use App\Repository\InstituteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: InstituteRepository::class)]
class Institute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['institute:read', 'teacher:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['institute:read', 'teacher:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'institutes')]
    private ?Organization $organization = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['institute:read', 'teacher:read'])]
    private ?string $reduction = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $teacherTotal = null;

    #[ORM\OneToMany(targetEntity: InstituteAnswer::class, mappedBy: 'institute')]
    private Collection $instituteAnswers;

    public function __construct()
    {
        $this->instituteAnswers = new ArrayCollection();
    }


    public function __toString(): string
    {
        return $this->getOrganization()->getName() . ' ' . $this->getName();
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

    /**
     * @return Collection<int, InstituteAnswer>
     */
    public function getInstituteAnswers(): Collection
    {
        return $this->instituteAnswers;
    }

    public function addInstituteAnswer(InstituteAnswer $instituteAnswer): static
    {
        if (!$this->instituteAnswers->contains($instituteAnswer)) {
            $this->instituteAnswers->add($instituteAnswer);
            $instituteAnswer->setInstitute($this);
        }

        return $this;
    }

    public function removeInstituteAnswer(InstituteAnswer $instituteAnswer): static
    {
        if ($this->instituteAnswers->removeElement($instituteAnswer)) {
            // set the owning side to null (unless already changed)
            if ($instituteAnswer->getInstitute() === $this) {
                $instituteAnswer->setInstitute(null);
            }
        }

        return $this;
    }
}
