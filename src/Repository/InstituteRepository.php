<?php

namespace App\Repository;

use App\Entity\Institute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Institute>
 *
 * @method Institute|null find($id, $lockMode = null, $lockVersion = null)
 * @method Institute|null findOneBy(array $criteria, array $orderBy = null)
 * @method Institute[]    findAll()
 * @method Institute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstituteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Institute::class);
    }

    /**
     * @return Institute[]
     */
    public function findByOrganization(int $orgId): array
    {
        return $this->createQueryBuilder('i')
            ->join('i.organization', 'o')
            ->where('o.id = :orgId')
            ->setParameter('orgId', $orgId)
            ->orderBy('i.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
