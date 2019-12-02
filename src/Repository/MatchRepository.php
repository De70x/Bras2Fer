<?php

namespace App\Repository;

use App\Entity\Match;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @method Match|null find($id, $lockMode = null, $lockVersion = null)
 * @method Match|null findOneBy(array $criteria, array $orderBy = null)
 * @method Match[]    findAll()
 * @method Match[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Match::class);
    }

    public function findMatchParJoueurs($j1, $j2){
        try {
            return $this->createQueryBuilder('m')
                ->select('count(m.id)')
                ->where('m.joueur1 = :j1')
                ->andWhere('m.joueur2 = :j2')
                ->setParameter('j1', $j1)
                ->setParameter('j2',$j2)
                ->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }

    public function scoreParJoueur($joueur){
        try {
            return $this->createQueryBuilder('m')
                ->select('count(m.id)')
                ->where('m.gagnant = :joueur')
                ->setParameter('joueur', $joueur)
                ->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }

    public function nombreMatchsParJoueur($joueur){
        try {
            return $this->createQueryBuilder('m')
                ->select('count(m.id)')
                ->where('m.joueur1 = :j1')
                ->orWhere('m.joueur2 = :j2')
                ->setParameter('j1', $joueur)
                ->setParameter('j2',$joueur)
                ->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }

    // /**
    //  * @return Match[] Returns an array of Match objects
    //  */
    /*
    public function findByExampleField($value)
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
    */

    /*
    public function findOneBySomeField($value): ?Match
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
