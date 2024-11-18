<?php

namespace App\Repository;

use App\Entity\instQoutLinkTitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<instQoutLinkTitle>
 *
 * @method instQoutLinkTitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method instQoutLinkTitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method instQoutLinkTitle[]    findAll()
 * @method instQoutLinkTitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstQwithLinkTitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, instQoutLinkTitle::class);
    }

//    /**
//     * @return InstQwithLinkTitle[] Returns an array of InstQwithLinkTitle objects
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

//    public function findOneBySomeField($value): ?InstQwithLinkTitle
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
