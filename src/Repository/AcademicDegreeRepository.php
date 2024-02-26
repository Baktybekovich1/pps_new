<?php

namespace App\Repository;

use App\Entity\AcademicDegree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AcademicDegree>
 *
 * @method AcademicDegree|null find($id, $lockMode = null, $lockVersion = null)
 * @method AcademicDegree|null findOneBy(array $criteria, array $orderBy = null)
 * @method AcademicDegree[]    findAll()
 * @method AcademicDegree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcademicDegreeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AcademicDegree::class);
    }

//    /**
//     * @return AcademicDegree[] Returns an array of AcademicDegree objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AcademicDegree
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
