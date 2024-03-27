<?php

namespace App\Repository;

use App\Entity\Prop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Prop>
 *
 * @method Prop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prop[]    findAll()
 * @method Prop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prop::class);
    }

   /**
    * @return Prop[] Returns an array of Prop objects
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

   public function findOneBySomeField($value): ?Prop
   {
       return $this->createQueryBuilder('m')
           ->andWhere('m.exampleField = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
