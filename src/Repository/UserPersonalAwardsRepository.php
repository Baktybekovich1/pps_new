<?php

namespace App\Repository;

use App\Entity\UserPersonalAwards;
use Couchbase\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserPersonalAwards>
 *
 * @method UserPersonalAwards|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPersonalAwards|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPersonalAwards[]    findAll()
 * @method UserPersonalAwards[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPersonalAwardsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPersonalAwards::class);
    }

//    /**
//     * @return UserPersonalAwardsController[] Returns an array of UserPersonalAwardsController objects
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

//    public function findOneBySomeField($value): ?UserPersonalAwardsController
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function save(UserPersonalAwards $awards)
    {
        $this->getEntityManager()->persist($awards);
        $this->getEntityManager()->flush();
    }

    public function remove(UserPersonalAwards $awards)
    {
        $this->getEntityManager()->remove($awards);
        $this->getEntityManager()->flush();
    }

    public function getUserPoints($userId): int|null
    {
        $qb = $this->createQueryBuilder('upa');

        $qb->select('SUM(subtitle.points) as points')
            ->leftJoin('upa.subtitle', 'subtitle')
            ->where('upa.status = :status')
            ->andWhere('upa.user = :userId') // если это связь с User сущностью
            ->setParameter('status', 'active')
            ->setParameter('userId', $userId)
            ->groupBy('upa.user');

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result['points'] ?? null;
    }


}
