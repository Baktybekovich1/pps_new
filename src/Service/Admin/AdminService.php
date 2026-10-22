<?php

namespace App\Service\Admin;

use App\Entity\Director;
use App\Repository\DirectorRepository;
use App\Repository\InstituteRepository;
use App\Repository\TeacherRepository;

readonly class AdminService
{
    public function __construct(
        private TeacherRepository   $teacherRepository,
        private InstituteRepository $instituteRepository, private DirectorRepository $directorRepository,
    )
    {
    }

    public function addDirector($teacherId, $instituteId): bool
    {
        // The institute is filtered to the admin's organization, and the
        // director has to work there too.
        $institute = $this->instituteRepository->find($instituteId);
        $teacher = null === $institute
            ? null
            : $this->teacherRepository->findOneWorkingFor((int) $teacherId, $institute->getOrganization());
        if (null === $teacher) {
            return false;
        }
        $director = new Director();
        $director->setInstitute($institute);
        $director->setTeacher($teacher);
        return $this->directorRepository->save($director);
    }

    public function removeDirector($directorId): bool
    {
        $director = $this->directorRepository->find($directorId);
        // getId() does not load the proxy; find() is filtered by organization.
        if (null === $director?->getInstitute() || null === $this->instituteRepository->find($director->getInstitute()->getId())) {
            return false;
        }
        $teacher = $director->getTeacher();
        if ($this->directorRepository->directorCount($teacher->getId()) <= 1) {
            $teacher->setRoles(["ROLE_TEACHER"]);
        }
        $this->teacherRepository->save($teacher);
        return $this->directorRepository->remove($director);
    }
}