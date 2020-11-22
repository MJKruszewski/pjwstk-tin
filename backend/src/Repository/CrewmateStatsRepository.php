<?php

namespace App\Repository;

use App\Entity\CrewmateStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CrewmateStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrewmateStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrewmateStats[]    findAll()
 * @method CrewmateStats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrewmateStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrewmateStats::class);
    }

    // /**
    //  * @return CrewmateStats[] Returns an array of CrewmateStats objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CrewmateStats
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
