<?php

namespace App\Repository;

use App\Entity\SoftwareInterventionReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SoftwareInterventionReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoftwareInterventionReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoftwareInterventionReport[]    findAll()
 * @method SoftwareInterventionReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoftwareInterventionReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoftwareInterventionReport::class);
    }

    // /**
    //  * @return SoftwareInterventionReport[] Returns an array of SoftwareInterventionReport objects
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
    public function findOneBySomeField($value): ?SoftwareInterventionReport
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
