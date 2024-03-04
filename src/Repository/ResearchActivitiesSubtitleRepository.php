<?php

namespace App\Repository;

use App\Entity\ResearchActivitiesSubtitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResearchActivitiesSubtitle>
 *
 * @method ResearchActivitiesSubtitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResearchActivitiesSubtitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResearchActivitiesSubtitle[]    findAll()
 * @method ResearchActivitiesSubtitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchActivitiesSubtitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResearchActivitiesSubtitle::class);
    }

//    /**
//     * @return ResearchActivitiesSubtitle[] Returns an array of ResearchActivitiesSubtitle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ResearchActivitiesSubtitle
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
