<?php

namespace App\Repository;

use App\Entity\UserInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserInfo>
 *
 * @method UserInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInfo[]    findAll()
 * @method UserInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserInfo::class);
    }

//    /**
//     * @return UserInfo[] Returns an array of UserInfo objects
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

//    public function findOneBySomeField($value): ?UserInfo
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function save(UserInfo $userInfo) {
        $this->getEntityManager()->persist($userInfo);
        $this->getEntityManager()->flush();
    }
    public function remove(UserInfo $userInfo) {
        $this->getEntityManager()->remove($userInfo);
        $this->getEntityManager()->flush();
    }
}
