<?php

namespace App\Repository;

use App\Entity\Intervention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Intervention|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intervention|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intervention[]    findAll()
 * @method Intervention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intervention::class);
    }

    // /**
    //  * @return Intervention[] Returns an array of Intervention objects
    //  */

    public function findAll()
    {
        return $this->findBy(array(), array('id' => 'DESC'));
    }

    public function findAllOngoing()
    {
        return $this->createQueryBuilder('i')
            ->andWhere("i.status != 'Terminée'")
            ->orderBy('i.status', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByClient($id)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.client = :id')
            ->setParameter('id', $id)
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByEquipment($id)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.equipment = :id')
            ->setParameter('id', $id)
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByOperatingSystem($id)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.operating_system = :id')
            ->setParameter('id', $id)
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByTask($id)
    {
        return $this->createQueryBuilder('i')
            ->andWhere(':id MEMBER OF i.tasks')
            ->setParameter('id', $id)
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByTechnician($id)
    {
        return $this->createQueryBuilder('i')
            ->andWhere(':id MEMBER OF i.technicians')
            ->setParameter('id', $id)
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Intervention
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
