<?php

namespace App\Repository;

use App\Entity\UserPersonalAwards;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserPersonalAwards>
 *
 * @method UserPersonalAwards|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPersonalAwards|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPersonalAwards[]    findAll()
 * @method UserPersonalAwards[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPersonalAwardsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPersonalAwards::class);
    }

//    /**
//     * @return UserPersonalAwardsController[] Returns an array of UserPersonalAwardsController objects
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

//    public function findOneBySomeField($value): ?UserPersonalAwardsController
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
