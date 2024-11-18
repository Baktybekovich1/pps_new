<?php

namespace App\Entity;

use App\Repository\UserInstQoutLinkSubtitleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserInstQoutLinkSubtitleRepository::class)]
class UserInstQoutLinkSubtitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userInstQoutLinkSubtitles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Institutions $institute = null;

    #[ORM\ManyToOne(inversedBy: 'userInstQoutLinkSubtitles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?InstQoutLinkSubtitle $award = null;

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

    public function setInstitute(?Institutions $institute): static
    {
        $this->institute = $institute;

        return $this;
    }

    public function getAward(): ?InstQoutLinkSubtitle
    {
        return $this->award;
    }

    public function setAward(?InstQoutLinkSubtitle $award): static
    {
        $this->award = $award;

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
