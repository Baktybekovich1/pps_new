<?php

namespace App\Repository;

use App\Entity\UserOffence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserOffence>
 *
 * @method UserOffence|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserOffence|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserOffence[]    findAll()
 * @method UserOffence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserOffenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOffence::class);
    }

//    /**
//     * @return UserOffence[] Returns an array of UserOffence objects
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

//    public function findOneBySomeField($value): ?UserOffence
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function save(UserOffence $userOffence)
    {
        $this->getEntityManager()->persist($userOffence);
        $this->getEntityManager()->flush();
    }
    public function remove(UserOffence $userOffence)
    {
        $this->getEntityManager()->remove($userOffence);
        $this->getEntityManager()->flush();
    }

}
