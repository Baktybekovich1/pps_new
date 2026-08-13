<?php

namespace App\Entity;

use App\Repository\StageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Tenant\OrganizationOwnedInterface;
use App\Tenant\OrganizationOwnedTrait;

#[ORM\Entity(repositoryClass: StageRepository::class)]
class Stage implements OrganizationOwnedInterface
{
    use OrganizationOwnedTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\OneToMany(targetEntity: QuestionTitle::class, mappedBy: 'stage')]
    private Collection $questionTitles;

    #[ORM\Column(nullable: true, options: ['default' => false])]
    private ?bool $isPermanent = false;

    public function __construct()
    {
        $this->questionTitles = new ArrayCollection();
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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, QuestionTitle>
     */
    public function getQuestionTitles(): Collection
    {
        return $this->questionTitles;
    }

    public function addQuestionTitle(QuestionTitle $questionTitle): static
    {
        if (!$this->questionTitles->contains($questionTitle)) {
            $this->questionTitles->add($questionTitle);
            $questionTitle->setStage($this);
        }

        return $this;
    }

    public function removeQuestionTitle(QuestionTitle $questionTitle): static
    {
        if ($this->questionTitles->removeElement($questionTitle)) {
            // set the owning side to null (unless already changed)
            if ($questionTitle->getStage() === $this) {
                $questionTitle->setStage(null);
            }
        }

        return $this;
    }

    public function isPermanent(): ?bool
    {
        return $this->isPermanent;
    }

    public function setIsPermanent(?bool $isPermanent): static
    {
        $this->isPermanent = $isPermanent;

        return $this;
    }
}
