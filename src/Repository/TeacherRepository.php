<?php

namespace App\Repository;

use App\Entity\Organization;
use App\Entity\QuestionTitle;
use App\Entity\Stage;
use App\Entity\Teacher;
use App\Entity\Years;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;


class TeacherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Teacher::class);
    }

    /**
     * A teacher belongs to an organization through a workplace there; the
     * account itself is shared. Null means every teacher, for a super admin.
     *
     * @return Teacher[]
     */
    public function findWorkingFor(?Organization $organization): array
    {
        $qb = $this->createQueryBuilder('teacher')->orderBy('teacher.lastName');
        if (null !== $organization) {
            $qb->where('teacher.id IN (
                    SELECT IDENTITY(workplace.teacher) FROM App\Entity\TeacherOrganization workplace
                    WHERE workplace.organization = :organization
                )')
                ->setParameter('organization', $organization);
        }

        return $qb->getQuery()->getResult();
    }

    public function findOneWorkingFor(int $id, ?Organization $organization): ?Teacher
    {
        $teacher = $this->find($id);
        if (null === $teacher || null === $organization) {
            return $teacher;
        }

        foreach ($teacher->getTeacherOrganizations() as $workplace) {
            if ($workplace->getOrganization()->getId() === $organization->getId()) {
                return $teacher;
            }
        }

        return null;
    }

    public function findTeacherTitlePointsTotal(Stage $stage, Teacher $teacher, ?Years $currentYear = null): int
    {
        $qb = $this->createQueryBuilder('teacher');

        $qb
            ->select('COALESCE(SUM(subtitle.point), 0) AS points')
            ->innerJoin('teacher.teacherAnswers', 'answers')
            ->innerJoin('answers.subtitle', 'subtitle')
            ->innerJoin('subtitle.title', 'title')
            ->innerJoin('title.stage', 'stage')
            ->where('stage = :stage')
            ->andWhere('teacher = :teacher')
            ->andWhere('answers.active = true')
            ->setParameter('stage', $stage)
            ->setParameter('teacher', $teacher);

        if ($currentYear) {
            $qb->andWhere('answers.academicYear = :currentYear OR stage.isPermanent = :isPermanent')
                ->setParameter('currentYear', $currentYear)
                ->setParameter('isPermanent', true);
        }

        $points = $qb->getQuery()->getSingleScalarResult();

        return (int) $points;
    }
    public function save(Teacher $teacher): bool
        {
            try {
                $this->getEntityManager()->persist($teacher);
                $this->getEntityManager()->flush();
                return true;
            }catch (\Throwable $exception){
                return false;
            }
        }

}
