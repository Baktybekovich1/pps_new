<?php

namespace App\Repository;

use App\Entity\InstQoutLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstQoutLink>
 *
 * @method InstQoutLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstQoutLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstQoutLink[]    findAll()
 * @method InstQoutLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstQoutLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstQoutLink::class);
    }

//    /**
//     * @return InstQoutLink[] Returns an array of InstQoutLink objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InstQoutLink
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
