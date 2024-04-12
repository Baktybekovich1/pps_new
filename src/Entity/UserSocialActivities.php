<?php

namespace App\Entity;

use App\Repository\UserSocialActivitiesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSocialActivitiesRepository::class)]
class UserSocialActivities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $link = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: SocialActivitiesSubtitle::class)]
    private ?SocialActivitiesSubtitle $socialActivitiesSubtitle;

    #[ORM\Column(length: 255)]
    private ?string $status = null;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }


    public function setUser(?User $user): void
    {
        $this->user = $user;
    }


    public function getSocialActivitiesSubtitle(): ?SocialActivitiesSubtitle
    {
        return $this->socialActivitiesSubtitle;
    }

    public function setSocialActivitiesSubtitle(?SocialActivitiesSubtitle $socialActivitiesSubtitle): void
    {
        $this->socialActivitiesSubtitle = $socialActivitiesSubtitle;
    }

    public function getPoints(): int
    {
        return $this->socialActivitiesSubtitle->getPoints();
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
