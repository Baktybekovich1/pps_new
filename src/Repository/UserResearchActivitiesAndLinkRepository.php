<?php

namespace App\Repository;

use App\Entity\UserResearchActivitiesAndLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserResearchActivitiesAndLink>
 *
 * @method UserResearchActivitiesAndLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserResearchActivitiesAndLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserResearchActivitiesAndLink[]    findAll()
 * @method UserResearchActivitiesAndLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserResearchActivitiesAndLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserResearchActivitiesAndLink::class);
    }

//    /**
//     * @return UserResearchActivitiesAndLink[] Returns an array of UserResearchActivitiesAndLink objects
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

//    public function findOneBySomeField($value): ?UserResearchActivitiesAndLink
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
