<?php

namespace App\Repository;

use App\Entity\UserInstQwithLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserInstQwithLink>
 *
 * @method UserInstQwithLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInstQwithLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInstQwithLink[]    findAll()
 * @method UserInstQwithLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInstQwithLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserInstQwithLink::class);
    }

//    /**
//     * @return UserInstQwithLink[] Returns an array of UserInstQwithLink objects
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

//    public function findOneBySomeField($value): ?UserInstQwithLink
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
