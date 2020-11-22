<?php

namespace App\Repository;

use App\Entity\ShipCrewmates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShipCrewmates|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipCrewmates|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipCrewmates[]    findAll()
 * @method ShipCrewmates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipCrewmatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipCrewmates::class);
    }

    // /**
    //  * @return ShipCrewmates[] Returns an array of ShipCrewmates objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ShipCrewmates
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
