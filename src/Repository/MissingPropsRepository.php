<?php

namespace App\Repository;

use App\Entity\props;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<props>
 *
 * @method props|null find($id, $lockMode = null, $lockVersion = null)
 * @method props|null findOneBy(array $criteria, array $orderBy = null)
 * @method props[]    findAll()
 * @method props[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class propsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, props::class);
    }

//    /**
//     * @return props[] Returns an array of props objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?props
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
