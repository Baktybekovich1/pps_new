<?php

namespace App\Repository;

use App\Entity\InnovativeEducationList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InnovativeEducationList>
 *
 * @method InnovativeEducationList|null find($id, $lockMode = null, $lockVersion = null)
 * @method InnovativeEducationList|null findOneBy(array $criteria, array $orderBy = null)
 * @method InnovativeEducationList[]    findAll()
 * @method InnovativeEducationList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InnovativeEducationListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InnovativeEducationList::class);
    }

//    /**
//     * @return InnovativeEducationList[] Returns an array of InnovativeEducationList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InnovativeEducationList
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function save(InnovativeEducationList $award)
{
    $this->getEntityManager()->persist($award);
    $this->getEntityManager()->flush();
}

    public function remove(InnovativeEducationList $awards)
    {
        $this->getEntityManager()->remove($awards);
        $this->getEntityManager()->flush();
    }
}
