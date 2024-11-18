<?php

namespace App\Repository;

use App\Entity\UserInstQwithLinkSubtitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserInstQwithLinkSubtitle>
 *
 * @method UserInstQwithLinkSubtitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInstQwithLinkSubtitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInstQwithLinkSubtitle[]    findAll()
 * @method UserInstQwithLinkSubtitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInstQwithLinkSubtitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserInstQwithLinkSubtitle::class);
    }

//    /**
//     * @return UserInstQwithLinkSubtitle[] Returns an array of UserInstQwithLinkSubtitle objects
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

//    public function findOneBySomeField($value): ?UserInstQwithLinkSubtitle
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
