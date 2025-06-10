<?php

namespace App\Repository;

use App\Entity\UserResearchActivitiesList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserResearchActivitiesList>
 *
 * @method UserResearchActivitiesList|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserResearchActivitiesList|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserResearchActivitiesList[]    findAll()
 * @method UserResearchActivitiesList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserResearchActivitiesListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserResearchActivitiesList::class);
    }

//    /**
//     * @return UserResearchActivitiesList[] Returns an array of UserResearchActivitiesList objects
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

//    public function findOneBySomeField($value): ?UserResearchActivitiesList
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function save(UserResearchActivitiesList $ural)
    {
        $this->getEntityManager()->persist($ural);
        $this->getEntityManager()->flush();
    }

    public function remove(UserResearchActivitiesList $research)
    {
        $this->getEntityManager()->remove($research);
        $this->getEntityManager()->flush();
    }

    public function getUserPoints($userId): int
    {
        $qb = $this->createQueryBuilder('ura');
        $qb->select('sum(subtitle.points) as points')
            ->leftJoin('ura.subtitle', 'subtitle')
            ->where('ura.user = :userId')
            ->andWhere('ura.status = :status')
            ->groupBy('ura.user')
            ->setParameter('userId', $userId)
            ->setParameter('status' , 'active');
        $result = $qb->getQuery()->getOneOrNullResult();
        return $result['points'] ?? 0;
    }
}
