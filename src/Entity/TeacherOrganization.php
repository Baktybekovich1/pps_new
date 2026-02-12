<?php

namespace App\Entity;

use App\Repository\TeacherOrganizationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TeacherOrganizationRepository::class)]
#[UniqueEntity(fields: ['teacher', 'organization'], message: 'Организация уже добавлена')]
#[ORM\UniqueConstraint(name: 'UNIQ_TEACHER_ORG', columns: ['teacher_id', 'organization_id'])]
class TeacherOrganization
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups('teacher:read')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'teacherOrganizations')]
    #[ORM\JoinColumn(nullable: false)]
    private Teacher $teacher;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('teacher:read')]
    private Organization $organization;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('teacher:read')]
    private Institute $institute;

    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Groups('teacher:read')]
    private ?bool $regular = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $active = null;

//    public function __construct(Teacher $teacher, Organization $org, Institute $inst)
//    {
//        $this->teacher = $teacher;
//        $this->organization = $org;
//        $this->institute = $inst;
//    }

    public function __toString(): string
    {
        return $this->organization->getName() . ' ' . $this->institute->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeacher(): Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(Teacher $t): self
    {
        $this->teacher = $t;
        return $this;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function setOrganization(Organization $o): self
    {
        $this->organization = $o;
        return $this;
    }

    public function getInstitute(): Institute
    {
        return $this->institute;
    }

    public function setInstitute(Institute $i): self
    {
        $this->institute = $i;
        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function getRegular(): ?bool
    {
        return $this->regular;
    }

    public function setRegular(?bool $regular): self
    {
        $this->regular = $regular;
        return $this;
    }

}