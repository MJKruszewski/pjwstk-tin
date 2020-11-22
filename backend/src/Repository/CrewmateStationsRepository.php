<?php

namespace App\Repository;

use App\Entity\CrewmateStations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CrewmateStations|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrewmateStations|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrewmateStations[]    findAll()
 * @method CrewmateStations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrewmateStationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrewmateStations::class);
    }

    // /**
    //  * @return CrewmateStations[] Returns an array of CrewmateStations objects
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
    public function findOneBySomeField($value): ?CrewmateStations
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
