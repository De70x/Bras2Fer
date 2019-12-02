<?php

namespace App\Repository;

use App\Entity\TableauFinal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TableauFinal|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableauFinal|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableauFinal[]    findAll()
 * @method TableauFinal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableauFinalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableauFinal::class);
    }

    // /**
    //  * @return TableauFinal[] Returns an array of TableauFinal objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TableauFinal
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
