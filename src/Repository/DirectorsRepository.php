<?php

namespace App\Repository;

use App\Entity\Directors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Directors>
 *
 * @method Directors|null find($id, $lockMode = null, $lockVersion = null)
 * @method Directors|null findOneBy(array $criteria, array $orderBy = null)
 * @method Directors[]    findAll()
 * @method Directors[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Directors::class);
    }

//    /**
//     * @return Directors[] Returns an array of Directors objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Directors
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
