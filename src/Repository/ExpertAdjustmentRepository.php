<?php

namespace App\Repository;

use App\Entity\ExpertAdjustment;
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

    public function getTeacherAdjustedPoints(int $teacherId): int
    {
        return (int)$this->createQueryBuilder('a')
            ->select('SUM(a.points)')
            ->where('a.targetTeacher = :teacherId')
            ->andWhere('a.isActive = :active')
            ->setParameter('teacherId', $teacherId)
            ->setParameter('active', true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getInstituteAdjustedPoints(int $instituteId): int
    {
        return (int)$this->createQueryBuilder('a')
            ->select('SUM(a.points)')
            ->where('a.targetInstitute = :instituteId')
            ->andWhere('a.isActive = :active')
            ->setParameter('instituteId', $instituteId)
            ->setParameter('active', true)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
