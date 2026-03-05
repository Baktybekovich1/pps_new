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
        $teacher = $this->teacherRepository->find($teacherId);
        $institute = $this->instituteRepository->find($instituteId);
        $director = new Director();
        $director->setInstitute($institute);
        $director->setTeacher($teacher);
        return $this->directorRepository->save($director);
    }

    public function removeDirector($directorId): bool
    {
        $director = $this->directorRepository->find($directorId);
        $teacher = $director->getTeacher();
        if ($this->directorRepository->directorCount($teacher) <= 1) {
            $teacher->setRoles(["ROLE_TEACHER"]);
        }
        $this->teacherRepository->save($teacher);
        return $this->directorRepository->remove($director);
    }
}