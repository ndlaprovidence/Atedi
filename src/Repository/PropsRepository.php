<?php

namespace App\Repository;

use App\Entity\Props;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Props>
 *
 * @method Props|null find($id, $lockMode = null, $lockVersion = null)
 * @method Props|null findOneBy(array $criteria, array $orderBy = null)
 * @method Props[]    findAll()
 * @method Props[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Props::class);
    }

   /**
    * @return Props[] Returns an array of Props objects
    */
   public function findByExampleField($value): array
   {
       return $this->createQueryBuilder('m')
           ->andWhere('m.exampleField = :val')
           ->setParameter('val', $value)
           ->orderBy('m.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   public function findOneBySomeField($value): ?Props
   {
       return $this->createQueryBuilder('m')
           ->andWhere('m.exampleField = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
