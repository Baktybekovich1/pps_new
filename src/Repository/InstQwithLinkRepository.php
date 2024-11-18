<?php

namespace App\Repository;

use App\Entity\InstQwithLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstQwithLink>
 *
 * @method InstQwithLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstQwithLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstQwithLink[]    findAll()
 * @method InstQwithLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstQwithLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstQwithLink::class);
    }

//    /**
//     * @return InstQwithLink[] Returns an array of InstQwithLink objects
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

//    public function findOneBySomeField($value): ?InstQwithLink
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
