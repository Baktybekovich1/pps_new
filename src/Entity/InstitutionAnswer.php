<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\InstitutionAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstitutionAnswerRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('PUBLIC_ACCESS')",
            securityMessage: "Пошёл ты",
        ),
        new Get(
            security: "is_granted('PUBLIC_ACCESS')",
            securityMessage: 'adasd'
        )
    ],
    security: "is_granted('ROLE_USER')",
    securityMessage: "Только авторизованные пользователи могут выполнять операции с этим ресурсом."
)]
class InstitutionAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'institutionAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Institutions $institution = null;

    #[ORM\ManyToOne(inversedBy: 'institutionAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?InstitutionQuestionOption $question = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $link = null;

    #[ORM\Column]
    private ?bool $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstitution(): ?Institutions
    {
        return $this->institution;
    }

    public function setInstitution(?Institutions $institution): static
    {
        $this->institution = $institution;

        return $this;
    }

    public function getQuestion(): ?InstitutionQuestionOption
    {
        return $this->question;
    }

    public function setQuestion(?InstitutionQuestionOption $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }
}
