<?php

namespace App\Repository;

use App\Entity\PersonalAwards;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PersonalAwards>
 *
 * @method PersonalAwards|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalAwards|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalAwards[]    findAll()
 * @method PersonalAwards[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalAwardsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonalAwards::class);
    }

//    /**
//     * @return PersonalAwards[] Returns an array of PersonalAwards objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PersonalAwards
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function save(PersonalAwards $award)
    {
        $this->getEntityManager()->persist($award);
        $this->getEntityManager()->flush();
    }

    public function remove(PersonalAwards $awards)
    {
        $this->getEntityManager()->remove($awards);
        $this->getEntityManager()->flush();
    }
}
