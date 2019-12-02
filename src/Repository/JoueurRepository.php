<?php

namespace App\Repository;

use App\Entity\Joueur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @method Joueur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Joueur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Joueur[]    findAll()
 * @method Joueur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoueurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Joueur::class);
    }

    public function getJoueursSansPoule($tournoi){
        return $this->createQueryBuilder('j')
            ->andWhere('j.poule is null')
            ->andWhere('j.tournoi = :tournoi')
            ->setParameter('tournoi', $tournoi)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $poule
     * Méthode qui sert a faire un qb pour la génération des listes
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQBJoueursParPoule($poule){
        return $this->createQueryBuilder('j')
            ->select('j')
            ->where('j.poule = :poule')
            ->setParameter('poule', $poule);
    }
    // /**
    //  * @return Joueur[] Returns an array of Joueur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Joueur
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getQBJoueur2ParPoule($idJoueur1, $poule)
    {
        return $this->createQueryBuilder('j')
            ->select('j')
            ->where('j.poule = :poule')
            ->andWhere('j.id != :idJoueur1')
            ->setParameter('poule', $poule)
            ->setParameter('idJoueur1', $idJoueur1);
    }
}
