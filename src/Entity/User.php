<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(targetEntity: Directors::class, mappedBy: '–≥—user')]
    private Collection $directors;

    #[ORM\OneToMany(targetEntity: ResultsOfYear::class, mappedBy: 'account')]
    private Collection $resultsOfYears;

    public function __construct()
    {
        $this->directors = new ArrayCollection();
        $this->resultsOfYears = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function  getUsername(): ?string
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
        return (string) $this->id;
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

    /**
     * @return Collection<int, Directors>
     */
    public function getDirectors(): Collection
    {
        return $this->directors;
    }

    public function addDirector(Directors $director): static
    {
        if (!$this->directors->contains($director)) {
            $this->directors->add($director);
            $director->set–≥—user($this);
        }

        return $this;
    }

    public function removeDirector(Directors $director): static
    {
        if ($this->directors->removeElement($director)) {
            // set the owning side to null (unless already changed)
            if ($director->get–≥—user() === $this) {
                $director->set–≥—user(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ResultsOfYear>
     */
    public function getResultsOfYears(): Collection
    {
        return $this->resultsOfYears;
    }

    public function addResultsOfYear(ResultsOfYear $resultsOfYear): static
    {
        if (!$this->resultsOfYears->contains($resultsOfYear)) {
            $this->resultsOfYears->add($resultsOfYear);
            $resultsOfYear->setAccount($this);
        }

        return $this;
    }

    public function removeResultsOfYear(ResultsOfYear $resultsOfYear): static
    {
        if ($this->resultsOfYears->removeElement($resultsOfYear)) {
            // set the owning side to null (unless already changed)
            if ($resultsOfYear->getAccount() === $this) {
                $resultsOfYear->setAccount(null);
            }
        }

        return $this;
    }
}
