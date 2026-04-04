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
class Teacher implements UserInterface
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = ['ROLE_TEACHER'];

//    #[ORM\Column(type: 'string', nullable: true)]
//    private ?string $password = null;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups('teacher:read')]
    private ?string $firstName = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups('teacher:read')]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('teacher:read')]
    private ?string $middleName = null;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups('teacher:read')]
    private Position $position;

    #[ORM\OneToMany(mappedBy: 'teacher', targetEntity: TeacherOrganization::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups('teacher:read')]
    /** @var Collection<int, TeacherOrganization> */
    private Collection $teacherOrganizations;

    #[ORM\OneToMany(targetEntity: TeacherAnswer::class, mappedBy: 'teacher')]
    private Collection $teacherAnswers;

    #[ORM\OneToOne(mappedBy: 'teacher', targetEntity: Expert::class, cascade: ['persist'])]
    private ?Expert $expert = null;

    #[ORM\OneToMany(targetEntity: ExpertAdjustment::class, mappedBy: 'targetTeacher')]
    private Collection $expertAdjustments;

    public function __construct()
    {
        $this->teacherOrganizations = new ArrayCollection();
        $this->teacherAnswers = new ArrayCollection();
        $this->expertAdjustments = new ArrayCollection();
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName() . ' ' . $this->getMiddleName();
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


    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

//    public function getPassword(): ?string
//    {
//        return $this->password;
//    }
//
//    public function setPassword(string $password): self
//    {
//        $this->password = $password;
//        return $this;
//    }


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


    public function getPosition(): Position
    {
        return $this->position;
    }

    public function setPosition(Position $p): self
    {
        $this->position = $p;
        return $this;
    }

    /**
     * @return Collection<int, TeacherAnswer>
     */
    public function getTeacherAnswers(): Collection
    {
        return $this->teacherAnswers;
    }

    public function addTeacherAnswer(TeacherAnswer $teacherAnswer): static
    {
        if (!$this->teacherAnswers->contains($teacherAnswer)) {
            $this->teacherAnswers->add($teacherAnswer);
            $teacherAnswer->setTeacher($this);
        }

        return $this;
    }

    public function removeTeacherAnswer(TeacherAnswer $teacherAnswer): static
    {
        if ($this->teacherAnswers->removeElement($teacherAnswer)) {
            // set the owning side to null (unless already changed)
            if ($teacherAnswer->getTeacher() === $this) {
                $teacherAnswer->setTeacher(null);
            }
        }

        return $this;
    }

    public function getExpert(): ?Expert
    {
        return $this->expert;
    }

    public function setExpert(?Expert $expert): static
    {
        // unset the owning side of the relation if necessary
        if ($expert === null && $this->expert !== null) {
            $this->expert->setTeacher(null);
        }

        // set the owning side of the relation if necessary
        if ($expert !== null && $expert->getTeacher() !== $this) {
            $expert->setTeacher($this);
        }

        $this->expert = $expert;

        return $this;
    }

    /**
     * @return Collection<int, ExpertAdjustment>
     */
    public function getExpertAdjustments(): Collection
    {
        return $this->expertAdjustments;
    }

    public function addExpertAdjustment(ExpertAdjustment $expertAdjustment): static
    {
        if (!$this->expertAdjustments->contains($expertAdjustment)) {
            $this->expertAdjustments->add($expertAdjustment);
            $expertAdjustment->setTargetTeacher($this);
        }

        return $this;
    }

    public function removeExpertAdjustment(ExpertAdjustment $expertAdjustment): static
    {
        if ($this->expertAdjustments->removeElement($expertAdjustment)) {
            // set the owning side to null (unless already changed)
            if ($expertAdjustment->getTargetTeacher() === $this) {
                $expertAdjustment->setTargetTeacher(null);
            }
        }

        return $this;
    }
}