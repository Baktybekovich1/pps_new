<?php

namespace App\Entity;

use App\Repository\TeacherOrganizationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TeacherOrganizationRepository::class)]
#[UniqueEntity(fields: ['teacher', 'organization'], message: 'Организация уже добавлена')]
#[ORM\UniqueConstraint(name: 'UNIQ_TEACHER_ORG', columns: ['teacher_id', 'organization_id'])]
class TeacherOrganization
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'teacherOrganizations')]
    #[ORM\JoinColumn(nullable: false)]
    private Teacher $teacher;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Organization $organization;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Institute $institute;

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
}