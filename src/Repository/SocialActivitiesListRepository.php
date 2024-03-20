<?php

namespace App\Repository;

use App\Entity\SocialActivitiesList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SocialActivitiesList>
 *
 * @method SocialActivitiesList|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocialActivitiesList|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocialActivitiesList[]    findAll()
 * @method SocialActivitiesList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocialActivitiesListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocialActivitiesList::class);
    }

//    /**
//     * @return SocialActivitiesList[] Returns an array of SocialActivitiesList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SocialActivitiesList
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function save(SocialActivitiesList $activitiesList)
    {
        $this->getEntityManager()->persist($activitiesList);
        $this->getEntityManager()->flush();
    }
}
