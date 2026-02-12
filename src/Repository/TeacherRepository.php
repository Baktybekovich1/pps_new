<?php

namespace App\Repository;

use App\Entity\QuestionTitle;
use App\Entity\Stage;
use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;


class TeacherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Teacher::class);
    }

    public function findTeacherTitlePointsTotal(Stage $stage, Teacher $teacher): int
    {
        $qb = $this->createQueryBuilder('teacher');

        $points = $qb
            ->select('COALESCE(SUM(subtitle.point), 0) AS points')
            ->innerJoin('teacher.teacherAnswers', 'answers')
            ->innerJoin('answers.subtitle', 'subtitle')
            ->innerJoin('subtitle.title', 'title')
            ->innerJoin('title.stage', 'stage')
            ->where('stage = :stage')
            ->andWhere('teacher = :teacher')
            ->setParameter('stage', $stage)
            ->setParameter('teacher', $teacher)
            ->getQuery()
            ->getSingleScalarResult();

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