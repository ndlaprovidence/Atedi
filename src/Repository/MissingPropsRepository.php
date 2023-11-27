<?php

namespace App\Repository;

use App\Entity\MissingProps;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MissingProps>
 *
 * @method MissingProps|null find($id, $lockMode = null, $lockVersion = null)
 * @method MissingProps|null findOneBy(array $criteria, array $orderBy = null)
 * @method MissingProps[]    findAll()
 * @method MissingProps[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissingPropsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MissingProps::class);
    }

//    /**
//     * @return MissingProps[] Returns an array of MissingProps objects
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

//    public function findOneBySomeField($value): ?MissingProps
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
