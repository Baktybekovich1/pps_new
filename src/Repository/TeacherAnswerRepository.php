<?php

namespace App\Repository;

use App\Entity\Teacher;
use App\Entity\TeacherAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TeacherAnswer>
 *
 * @method TeacherAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeacherAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeacherAnswer[]    findAll()
 * @method TeacherAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeacherAnswer::class);
    }

    public function getTeacherPointsCount(Teacher $teacher): int
    {
        return (int)$this->createQueryBuilder('answer')
            ->select('COALESCE(SUM(subtitle.point), 0)')
            ->innerJoin('answer.teacher', 'teacher')
            ->innerJoin('answer.subtitle', 'subtitle')
            ->where('teacher = :eteacher')
            ->setParameter('eteacher', $teacher)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
