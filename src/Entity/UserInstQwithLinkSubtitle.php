<?php

namespace App\Entity;

use App\Repository\UserInstQwithLinkSubtitleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserInstQwithLinkSubtitleRepository::class)]
class UserInstQwithLinkSubtitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userInstQwithLinkSubtitles')]
    private ?Institutions $institute = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?InstQwithLinkSubtitle $award = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $link = null;

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

    public function getAward(): ?InstQwithLinkSubtitle
    {
        return $this->award;
    }

    public function setAward(?InstQwithLinkSubtitle $award): static
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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }
}
