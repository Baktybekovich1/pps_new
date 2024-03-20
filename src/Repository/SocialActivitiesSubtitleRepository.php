<?php

namespace App\Repository;

use App\Entity\SocialActivitiesSubtitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SocialActivitiesSubtitle>
 *
 * @method SocialActivitiesSubtitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocialActivitiesSubtitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocialActivitiesSubtitle[]    findAll()
 * @method SocialActivitiesSubtitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocialActivitiesSubtitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocialActivitiesSubtitle::class);
    }

//    /**
//     * @return SocialActivitiesSubtitle[] Returns an array of SocialActivitiesSubtitle objects
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

//    public function findOneBySomeField($value): ?SocialActivitiesSubtitle
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function save(SocialActivitiesSubtitle $activitiesSubtitle)
    {
        $this->getEntityManager()->persist($activitiesSubtitle);
        $this->getEntityManager()->flush();
    }
}
