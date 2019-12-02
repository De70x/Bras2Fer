<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatchRepository")
 * @ORM\Table(name="matchs")
 */
class Match
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Joueur
     * @ORM\ManyToOne(targetEntity="App\Entity\Joueur")
     */
    private $joueur1;

    /**
     * @var Joueur
     * @ORM\ManyToOne(targetEntity="App\Entity\Joueur")
     */
    private $joueur2;

    /**
     * @var Joueur
     * @ORM\ManyToOne(targetEntity="App\Entity\Joueur")
     */
    private $gagnant;

    /**
     * @var Poule
     * @ORM\ManyToOne(targetEntity="App\Entity\Poule")
     */
    private $poule;

    /**
     * @return Joueur
     */
    public function getJoueur1(): ?Joueur
    {
        return $this->joueur1;
    }

    /**
     * @param Joueur $joueur1
     */
    public function setJoueur1(Joueur $joueur1): void
    {
        $this->joueur1 = $joueur1;
    }

    /**
     * @return Joueur
     */
    public function getJoueur2(): ?Joueur
    {
        return $this->joueur2;
    }

    /**
     * @param Joueur $joueur2
     */
    public function setJoueur2(Joueur $joueur2): void
    {
        $this->joueur2 = $joueur2;
    }

    /**
     * @return Joueur
     */
    public function getGagnant(): ?Joueur
    {
        return $this->gagnant;
    }

    /**
     * @param Joueur $gagnant
     */
    public function setGagnant(Joueur $gagnant): void
    {
        $this->gagnant = $gagnant;
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
    public function setPoule(Poule $poule): void
    {
        $this->poule = $poule;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
