<?php

namespace App\Repository;

use App\Entity\UserSocialActivities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserSocialActivities>
 *
 * @method UserSocialActivities|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSocialActivities|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSocialActivities[]    findAll()
 * @method UserSocialActivities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSocialActivitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSocialActivities::class);
    }

//    /**
//     * @return UserSocialActivities[] Returns an array of UserSocialActivities objects
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

//    public function findOneBySomeField($value): ?UserSocialActivities
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function save(UserSocialActivities $userSocialActivities)
    {
        $this->getEntityManager()->persist($userSocialActivities);
        $this->getEntityManager()->flush();
    }

}
