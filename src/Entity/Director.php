<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\DirectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: DirectorRepository::class)]
#[ApiResource(operations: [
    new GetCollection(
        security: "is_granted('ROLE_USER')",
        securityMessage: "Только авторизованные пользователи могут получить список продуктов."
    ),
    new Post(
        security: "is_granted('ROLE_ADMIN')",
        securityMessage: "Только администраторы могут добавлять продукты."
    ),
    new Get(
        security: "is_granted('ROLE_USER')",
        securityMessage: "Только авторизованные пользователи могут получить этот ресурс."
    ),
    new Patch(
        security: "is_granted('ROLE_ADMIN')",
        securityMessage: "Только администраторы могут редактировать ресурсы."
    ),
    new Delete(
        security: "is_granted('ROLE_ADMIN')",
        securityMessage: "Только администраторы могут удалять ресурсы."
    )
])]
class Director
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'director')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'director')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Institutions $institutions = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        $user->setRoles(['ROLE_DIRECTOR']);

        return $this;
    }

    public function getInstitutions(): ?Institutions
    {
        return $this->institutions;
    }

    public function setInstitutions(?Institutions $institutions): static
    {
        $this->institutions = $institutions;

        return $this;
    }
}
