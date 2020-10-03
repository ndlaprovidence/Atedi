<?php

namespace App\Repository;

use App\Entity\InterventionReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InterventionReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterventionReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterventionReport[]    findAll()
 * @method InterventionReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InterventionReport::class);
    }
    
    public function findAllByTechnician($id)
    {
        return $this->createQueryBuilder('ir')
            ->andWhere(':id MEMBER OF ir.technicians')
            ->setParameter('id', $id)
            ->orderBy('ir.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
