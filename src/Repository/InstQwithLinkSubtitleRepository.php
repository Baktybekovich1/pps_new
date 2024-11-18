<?php

namespace App\Repository;

use App\Entity\InstQwithLinkSubtitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstQwithLinkSubtitle>
 *
 * @method InstQwithLinkSubtitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstQwithLinkSubtitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstQwithLinkSubtitle[]    findAll()
 * @method InstQwithLinkSubtitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstQwithLinkSubtitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstQwithLinkSubtitle::class);
    }

//    /**
//     * @return InstQwithLinkSubtitle[] Returns an array of InstQwithLinkSubtitle objects
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

//    public function findOneBySomeField($value): ?InstQwithLinkSubtitle
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
