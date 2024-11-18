<?php

namespace App\Repository;

use App\Entity\UserInstQoutLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserInstQoutLink>
 *
 * @method UserInstQoutLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInstQoutLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInstQoutLink[]    findAll()
 * @method UserInstQoutLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInstQoutLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserInstQoutLink::class);
    }

//    /**
//     * @return UserInstQoutLink[] Returns an array of UserInstQoutLink objects
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

//    public function findOneBySomeField($value): ?UserInstQoutLink
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
