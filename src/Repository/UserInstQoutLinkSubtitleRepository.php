<?php

namespace App\Repository;

use App\Entity\UserInstQoutLinkSubtitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserInstQoutLinkSubtitle>
 *
 * @method UserInstQoutLinkSubtitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInstQoutLinkSubtitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInstQoutLinkSubtitle[]    findAll()
 * @method UserInstQoutLinkSubtitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInstQoutLinkSubtitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserInstQoutLinkSubtitle::class);
    }

//    /**
//     * @return UserInstQoutLinkSubtitle[] Returns an array of UserInstQoutLinkSubtitle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserInstQoutLinkSubtitle
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
