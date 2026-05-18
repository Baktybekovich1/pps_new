<?php

namespace App\Repository;

use App\Entity\ExpertAdjustment;
use App\Entity\Years;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExpertAdjustment>
 *
 * @method ExpertAdjustment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpertAdjustment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpertAdjustment[]    findAll()
 * @method ExpertAdjustment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpertAdjustmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpertAdjustment::class);
    }

    public function save(ExpertAdjustment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ExpertAdjustment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getTeacherAdjustedPoints(int $teacherId, ?Years $year = null): int
    {
        $qb = $this->createQueryBuilder('a')
            ->select('SUM(a.points)')
            ->where('a.targetTeacher = :teacherId')
            ->andWhere('a.isActive = :active')
            ->setParameter('teacherId', $teacherId)
            ->setParameter('active', true);

        if ($year) {
            $qb->andWhere('a.academicYear = :year')
                ->setParameter('year', $year);
        }

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    public function getInstituteAdjustedPoints(int $instituteId, ?Years $year = null): int
    {
        $qb = $this->createQueryBuilder('a')
            ->select('SUM(a.points)')
            ->where('a.targetInstitute = :instituteId')
            ->andWhere('a.isActive = :active')
            ->setParameter('instituteId', $instituteId)
            ->setParameter('active', true);

        if ($year) {
            $qb->andWhere('a.academicYear = :year')
                ->setParameter('year', $year);
        }

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    public function findActiveByTeacherAndYear(\App\Entity\Teacher $teacher, ?Years $year = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.targetTeacher = :teacher')
            ->andWhere('a.isActive = :active')
            ->setParameter('teacher', $teacher)
            ->setParameter('active', true)
            ->orderBy('a.createdAt', 'DESC');

        if ($year) {
            $qb->andWhere('a.academicYear = :year')
                ->setParameter('year', $year);
        }

        return $qb->getQuery()->getResult();
    }
}
