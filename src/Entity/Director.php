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

    #[ORM\ManyToOne(targetEntity: Teacher::class, cascade: ['persist', 'remove'], inversedBy: 'director')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Teacher $teacher = null;

    #[ORM\ManyToOne(inversedBy: 'director')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Institute $institute = null;


    public function __construct()
    {
        $this->teacher = new Teacher();   // ← пустой объект, поля заполняются формой
    }

    public function __toString(): string
    {
        return $this->teacher->getFirstName() . ' ' . $this->teacher->getLastName() . ' ' . $this->teacher->getMiddleName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): static
    {
        $this->teacher = $teacher;
        $teacher->setRoles(['ROLE_DIRECTOR']);

        return $this;
    }

    public function getInstitute(): ?Institute
    {
        return $this->institute;
    }

    public function setInstitute(?Institute $institute): static
    {
        $this->institute = $institute;

        return $this;
    }
}
