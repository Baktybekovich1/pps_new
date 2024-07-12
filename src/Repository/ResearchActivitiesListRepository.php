<?php

namespace App\Repository;

use App\Entity\ResearchActivitiesList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResearchActivitiesList>
 *
 * @method ResearchActivitiesList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResearchActivitiesList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResearchActivitiesList[]    findAll()
 * @method ResearchActivitiesList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchActivitiesListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResearchActivitiesList::class);
    }

//    /**
//     * @return ResearchActivitiesList[] Returns an array of ResearchActivitiesList objects
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

//    public function findOneBySomeField($value): ?ResearchActivitiesList
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function save(ResearchActivitiesList $award): void
    {
        $this->getEntityManager()->persist($award);
        $this->getEntityManager()->flush();
    }

    public function remove(ResearchActivitiesList $awards): void
    {
        $this->getEntityManager()->remove($awards);
        $this->getEntityManager()->flush();
    }
}
