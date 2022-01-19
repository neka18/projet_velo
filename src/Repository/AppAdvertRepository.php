<?php

namespace App\Repository;

use App\Entity\AppAdvert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppAdvert|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppAdvert|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppAdvert[]    findAll()
 * @method AppAdvert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppAdvertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppAdvert::class);
    }

    // /**
    //  * @return AppAdvert[] Returns an array of AppAdvert objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AppAdvert
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
