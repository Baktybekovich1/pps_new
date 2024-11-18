<?php

namespace App\Entity;

use App\Repository\UserInstQoutLinkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserInstQoutLinkRepository::class)]
class UserInstQoutLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userInstQoutLinks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Institutions $institute = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?InstQoutLink $award = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstitute(): ?Institutions
    {
        return $this->institute;
    }

    public function setInstitute(?Institutions $institute): static
    {
        $this->institute = $institute;

        return $this;
    }

    public function getAward(): ?InstQoutLink
    {
        return $this->award;
    }

    public function setAward(?InstQoutLink $award): static
    {
        $this->award = $award;

        return $this;
    }
}
