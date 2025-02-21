<?php

namespace App\Repository;

use App\Entity\UserExpertPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserExpertPoint>
 *
 * @method UserExpertPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserExpertPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserExpertPoint[]    findAll()
 * @method UserExpertPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserExpertPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserExpertPoint::class);
    }

//    /**
//     * @return UserExpertPoint[] Returns an array of UserExpertPoint objects
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

//    public function findOneBySomeField($value): ?UserExpertPoint
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
