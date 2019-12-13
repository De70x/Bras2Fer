<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PouleRepository")
 */
class Poule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $nom;

    /**
     * @var Tournoi
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournoi", inversedBy="poules")
     */
    private $tournoi;

    /**
     * @param Tournoi $tournoi
     */
    public function setTournoi(Tournoi $tournoi): void
    {
        $this->tournoi = $tournoi;
    }

    /**
     * @return Tournoi
     */
    public function getTournoi(): ?Tournoi
    {
        return $this->tournoi;
    }

    /**
     * @var Joueur[]
     * @ORM\OneToMany(targetEntity="App\Entity\Joueur", mappedBy="poule")
     */
    private $joueurs;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
    }

    /**
     * @return Joueur[]
     */
    public function getJoueurs()
    {
        $joueursTries = $this->joueurs->toArray();
        usort($joueursTries, array("App\\Entity\\Joueur", "comparator"));
        return $joueursTries;
    }

    /**
     * @param Joueur[] $joueurs
     */
    public function setJoueurs(array $joueurs): void
    {
        $this->joueurs = $joueurs;
    }

    public function getId(): ?int
    {
        return $this->id;
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
}
