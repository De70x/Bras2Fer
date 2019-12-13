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

    public function getScore(MatchRepository $matchRepository){
        return $matchRepository->scoreParJoueur($this);
    }

    public function getNombreMatchs(MatchRepository $matchRepository){
        return $matchRepository->nombreMatchsParJoueur($this);
    }

    public function isPremier(MatchRepository $matchRepository){
        return $matchRepository->isPremier($this);
    }
}
