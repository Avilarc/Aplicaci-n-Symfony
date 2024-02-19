<?php

namespace App\Repository;

use App\Entity\Reabastece;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reabastece>
 *
 * @method Reabastece|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reabastece|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reabastece[]    findAll()
 * @method Reabastece[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reabastece::class);
    }

//    /**
//     * @return Reabastece[] Returns an array of Reabastece objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reabastece
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
