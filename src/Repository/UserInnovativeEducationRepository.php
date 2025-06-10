<?php

namespace App\Repository;

use App\Entity\UserInnovativeEducation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserInnovativeEducation>
 *
 * @method UserInnovativeEducation|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInnovativeEducation|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInnovativeEducation[]    findAll()
 * @method UserInnovativeEducation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInnovativeEducationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserInnovativeEducation::class);
    }

//    /**
//     * @return UserInnovativeEducation[] Returns an array of UserInnovativeEducation objects
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

//    public function findOneBySomeField($value): ?UserInnovativeEducation
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function save(UserInnovativeEducation $education)
    {
        $this->getEntityManager()->persist($education);
        $this->getEntityManager()->flush();

    }

    public function remove(UserInnovativeEducation $innovative)
    {
        $this->getEntityManager()->remove($innovative);
        $this->getEntityManager()->flush();
    }

    public function getUserPoints($userId): int
    {
        $qb = $this->createQueryBuilder('uie');

        $qb->select('SUM(subtitle.points) as points')
            ->leftJoin('uie.innovativeEducationSubtitle', 'subtitle')
            ->where('uie.user = :userId')
            ->andWhere('uie.status = :status')
            ->groupBy('uie.user')
            ->setParameter('userId', $userId)
            ->setParameter('status', 'active');
        $result = $qb->getQuery()->getOneOrNullResult();

        return $result['points'] ?? 0;
    }
}
