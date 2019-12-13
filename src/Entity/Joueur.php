<?php

namespace App\Entity;

use App\Repository\MatchRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JoueurRepository")
 */
class Joueur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @var Poule
     * @ORM\ManyToOne(targetEntity="App\Entity\Poule", inversedBy="joueurs")
     */
    private $poule;

    /**
     * @var Tournoi
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournoi", inversedBy="joueurs")
     */
    private $tournoi;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @return Tournoi
     */
    public function getTournoi(): ?Tournoi
    {
        return $this->tournoi;
    }

    /**
     * @param Tournoi $tournoi
     */
    public function setTournoi(Tournoi $tournoi): void
    {
        $this->tournoi = $tournoi;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Poule
     */
    public function getPoule(): Poule
    {
        return $this->poule;
    }

    /**
     * @param Poule $poule
     */
    public function setPoule(Poule $poule=null): void
    {
        $this->poule = $poule;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function __toString()
    {
        $vRet = $this->getNom();

        return $vRet;
    }

    public function getNombreMatchs(MatchRepository $matchRepository){
        return $matchRepository->nombreMatchsParJoueur($this);
    }

    public function isPremier(MatchRepository $matchRepository){
        return $matchRepository->isPremier($this);
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function comparator(Joueur $object1, Joueur $object2) {

        if ($object1->getScore() == $object2->getScore()) {
            return 0;
        }
        return ($object1->getScore() < $object2->getScore()) ? 1 : -1;
    }

    /**
     * méthode qui permet de savoir si un joueur est normalement qualifié
     * on offre toujours le choix ensuite de rectifier
     * @return bool
     */
    public function estQualifie(){
        $nbJoueursDansPoule = sizeof($this->getPoule()->getJoueurs());
        $palier = round($nbJoueursDansPoule/2);
        $joueursPoule = $this->getPoule()->getJoueurs();
        $vRet = false;
        // les joueurs de la méthode getJoueurs sont déjà triés
        // on itère donc jusqu'au palier et si le joueur est rencontré on return true
        for($i = 0; $i<$palier; $i++){
            if($joueursPoule[$i] === $this)
            {
                $vRet = true;
            }
        }

        return $vRet;
    }
}
