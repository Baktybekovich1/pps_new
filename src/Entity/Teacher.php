<?php

namespace App\Entity;

use App\Repository\TeacherRepository;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TeacherRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_EMAIL', fields: ['email'])]
class Teacher implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = ['ROLE_TEACHER'];

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 6, nullable: true)]
    private ?string $signupCode = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $codeExpiredAt = null;

    #[ORM\Column(type: 'boolean')]
    private bool $enabled = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups('teacher:read')]
    private ?string $firstName = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups('teacher:read')]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('teacher:read')]
    private ?string $middleName = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Groups('teacher:read')]
    private ?bool $hasTrousers = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('teacher:read')]
    private Position $position;

    #[ORM\OneToMany(mappedBy: 'teacher', targetEntity: TeacherOrganization::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups('teacher:read')]
    /** @var Collection<int, TeacherOrganization> */
    private Collection $teacherOrganizations;

    public function __construct()
    {
        $this->teacherOrganizations = new ArrayCollection();
    }

    /** @return Collection<int,TeacherOrganization> */
    public function getTeacherOrganizations(): Collection
    {
        return $this->teacherOrganizations;
    }

    public function addTeacherOrganization(TeacherOrganization $to): self
    {
        if (!$this->teacherOrganizations->contains($to)) {
            $this->teacherOrganizations[] = $to;
            $to->setTeacher($this);
        }
        return $this;
    }

    public function removeTeacherOrganization(TeacherOrganization $to): self
    {
        $this->teacherOrganizations->removeElement($to);
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function eraseCredentials(): void
    {
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getSignupCode(): ?string
    {
        return $this->signupCode;
    }

    public function setSignupCode(?string $code): self
    {
        $this->signupCode = $code;
        return $this;
    }

    public function getCodeExpiredAt(): ?\DateTimeImmutable
    {
        return $this->codeExpiredAt;
    }

    public function setCodeExpiredAt(?\DateTimeImmutable $dt): self
    {
        $this->codeExpiredAt = $dt;
        return $this;
    }

    public function isCodeExpired(): bool
    {
        return $this->codeExpiredAt === null || $this->codeExpiredAt < new \DateTimeImmutable();
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $v): self
    {
        $this->firstName = $v;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $v): self
    {
        $this->lastName = $v;
        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(?string $m): self
    {
        $this->middleName = $m;
        return $this;
    }

    public function getHasTrousers(): ?bool
    {
        return $this->hasTrousers;
    }

    public function setHasTrousers(?bool $v): self
    {
        $this->hasTrousers = $v;
        return $this;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function setPosition(Position $p): self
    {
        $this->position = $p;
        return $this;
    }
}