<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    #[ApiProperty(readable: false, writable: false)]
    private array $roles = [];

    #[ORM\Column]
    #[ApiProperty(readable: false, writable: false)]
    private ?string $password = null;

    #[ORM\OneToOne(targetEntity: UserInfo::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private $userInfo;
    #[ORM\OneToMany(targetEntity: Director::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Collection $director;

    #[ORM\OneToOne(mappedBy: 'account', cascade: ['persist', 'remove'])]
    private ?Expert $expert = null;

    #[ORM\OneToMany(targetEntity: UserExpertPoint::class, mappedBy: 'teacher')]
    private Collection $userExpertPoints;

    public function __construct()
    {
        $this->director = new ArrayCollection();
        $this->userExpertPoints = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->username ?? 'Unnamed User';
    }

    public function getUserInfo(): string
    {
        return $this->userInfo->getName() ?? 'Unnamed User';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->id;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getExpert(): ?Expert
    {
        return $this->expert;
    }

    public function setExpert(?Expert $expert): static
    {
        // unset the owning side of the relation if necessary
        if ($expert === null && $this->expert !== null) {
            $this->expert->setAccount(null);
        }

        // set the owning side of the relation if necessary
        if ($expert !== null && $expert->getAccount() !== $this) {
            $expert->setAccount($this);
        }

        $this->expert = $expert;

        return $this;
    }

    /**
     * @return Collection<int, UserExpertPoint>
     */
    public function getUserExpertPoints(): Collection
    {
        return $this->userExpertPoints;
    }

    public function addUserExpertPoint(UserExpertPoint $userExpertPoint): static
    {
        if (!$this->userExpertPoints->contains($userExpertPoint)) {
            $this->userExpertPoints->add($userExpertPoint);
            $userExpertPoint->setTeacher($this);
        }

        return $this;
    }

    public function removeUserExpertPoint(UserExpertPoint $userExpertPoint): static
    {
        if ($this->userExpertPoints->removeElement($userExpertPoint)) {
            // set the owning side to null (unless already changed)
            if ($userExpertPoint->getTeacher() === $this) {
                $userExpertPoint->setTeacher(null);
            }
        }

        return $this;
    }



}
