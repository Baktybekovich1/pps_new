<?php

namespace App\Repository;

use App\Entity\UserExpertPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserExpertPoint>
 *
 * @method UserExpertPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserExpertPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserExpertPoint[]    findAll()
 * @method UserExpertPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserExpertPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserExpertPoint::class);
    }

    public function save(UserExpertPoint $userExpertPoint): void
    {
        $this->getEntityManager()->persist($userExpertPoint);
        $this->getEntityManager()->flush();
    }

    public function remove(UserExpertPoint $userExpertPoint): void
    {
        $this->getEntityManager()->remove($userExpertPoint);
        $this->getEntityManager()->flush();
    }
}
