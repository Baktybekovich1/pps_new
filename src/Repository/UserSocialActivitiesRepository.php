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
    public function save(UserSocialActivities $userSocialActivities): void
    {
        $this->getEntityManager()->persist($userSocialActivities);
        $this->getEntityManager()->flush();
    }

    public function remove(UserSocialActivities $social): void
    {
        $this->getEntityManager()->remove($social);
        $this->getEntityManager()->flush();
    }

    public function getUserPoints($userId): int
    {
        $qb = $this->createQueryBuilder('usa');
        $qb->select('sum(subtitle.points) as points')
            ->leftJoin('usa.socialActivitiesSubtitle', 'subtitle')
            ->where('usa.user = :userId')
            ->andWhere('usa.status = :status')
            ->groupBy('usa.user')
            ->setParameter('userId', $userId)
            ->setParameter('status' , 'active');
        $result = $qb->getQuery()->getOneOrNullResult();
        return $result['points'] ?? 0;
    }

}
