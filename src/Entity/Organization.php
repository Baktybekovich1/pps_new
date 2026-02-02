<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Attribute as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\SerializedName;


#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Организация с таким названием уже есть')]
#[Vich\Uploadable]
class Organization
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['organization:read','teacher:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['organization:read','teacher:read'])]
    private string $name;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoFilename = null;   // только имя файла

    #[Vich\UploadableField(mapping: 'organization_photo', fileNameProperty: 'photoFilename')]
    private ?File $photoFile = null;         // объект файла (не persisted)

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $photoUpdatedAt = null;   // для Vich

    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: Institute::class, orphanRemoval: true)]
    private Collection $institutes;

    #[Groups(['organization:read','teacher:read'])]
    #[SerializedName('photoUrl')]
    public function getPhotoUrl(): ?string
    {
        if (!$this->photoFilename) {
            return null;
        }

        return '/uploads/organizations/' . $this->photoFilename;
    }
    public function __construct()
    {
        $this->institutes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }


    public function getInstitutes(): Collection
    {
        return $this->institutes;
    }

    public function getPhotoFilename(): ?string { return $this->photoFilename; }
    public function setPhotoFilename(?string $photoFilename): self { $this->photoFilename = $photoFilename; return $this; }

    public function getPhotoFile(): ?File { return $this->photoFile; }
    public function setPhotoFile(?File $photoFile): self
    {
        $this->photoFile = $photoFile;
        if ($photoFile) {
            $this->photoUpdatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getPhotoUpdatedAt(): ?\DateTimeImmutable { return $this->photoUpdatedAt; }
}
