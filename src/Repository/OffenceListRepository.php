<?php

namespace App\Repository;

use App\Entity\OffenceList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OffenceList>
 *
 * @method OffenceList|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffenceList|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffenceList[]    findAll()
 * @method OffenceList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffenceListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OffenceList::class);
    }

//    /**
//     * @return OffenceList[] Returns an array of OffenceList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OffenceList
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
