<?php

namespace App\Repository;

use App\Entity\UserResearchActivities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserResearchActivities>
 *
 * @method UserResearchActivities|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserResearchActivities|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserResearchActivities[]    findAll()
 * @method UserResearchActivities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserResearchActivitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserResearchActivities::class);
    }

//    /**
//     * @return UserResearchActivities[] Returns an array of UserResearchActivities objects
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

//    public function findOneBySomeField($value): ?UserResearchActivities
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function save(UserResearchActivities $activities)
    {
        $this->getEntityManager()->persist($activities);
        $this->getEntityManager()->flush();

    }
}
