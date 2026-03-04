<?php

namespace App\Entity;

use App\Repository\InstituteQuestionSubtitleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstituteQuestionSubtitleRepository::class)]
class InstituteQuestionSubtitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'subtitle')]
    private ?InstituteQuestionTitle $title = null;

    #[ORM\Column]
    private ?int $point = null;

    #[ORM\OneToMany(targetEntity: InstituteAnswer::class, mappedBy: 'subtitle')]
    private Collection $instituteAnswers;

    public function __construct()
    {
        $this->instituteAnswers = new ArrayCollection();
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

    public function getTitle(): ?InstituteQuestionTitle
    {
        return $this->title;
    }

    public function setTitle(?InstituteQuestionTitle $title): static
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
            $instituteAnswer->setSubtitle($this);
        }

        return $this;
    }

    public function removeInstituteAnswer(InstituteAnswer $instituteAnswer): static
    {
        if ($this->instituteAnswers->removeElement($instituteAnswer)) {
            // set the owning side to null (unless already changed)
            if ($instituteAnswer->getSubtitle() === $this) {
                $instituteAnswer->setSubtitle(null);
            }
        }

        return $this;
    }
}
