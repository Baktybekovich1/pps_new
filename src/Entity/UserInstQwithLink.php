<?php

namespace App\Entity;

use App\Repository\UserInstQwithLinkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserInstQwithLinkRepository::class)]
class UserInstQwithLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userInstQwithLinks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Institutions $institute = null;

    #[ORM\ManyToOne(targetEntity: InstQwithLink::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?InstQwithLink $award = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $link = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstitute(): ?Institutions
    {
        return $this->institute;
    }

    public function setInstitute(?Institutions $institute): void
    {
        $this->institute = $institute;
    }


    public function getAward(): ?InstQwithLink
    {
        return $this->award;
    }

    public function setAward(?InstQwithLink $award): static
    {
        $this->award = $award;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
