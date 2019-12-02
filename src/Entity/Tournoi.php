<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TournoiRepository")
 */
class Tournoi
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @var Poule[]
     * @ORM\OneToMany(targetEntity="App\Entity\Poule", mappedBy="tournoi")
     */
    private $poules;

    /**
     * @var Joueur[]
     * @ORM\OneToMany(targetEntity="App\Entity\Joueur", mappedBy="tournoi")
     */
    private $joueurs;

    /**
     * @return Joueur[]
     */
    public function getJoueurs(): ?array
    {
        return $this->joueurs;
    }

    /**
     * @param Joueur[] $joueurs
     */
    public function setJoueurs(array $joueurs): void
    {
        $this->joueurs = $joueurs;
    }

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->poules = new ArrayCollection();
        $this->joueurs = new ArrayCollection();
    }

    /**
     * @param Poule[] $poules
     */
    public function setPoules(array $poules): void
    {
        $this->poules = $poules;
    }

    /**
     * @return Poule[]
     */
    public function getPoules()
    {
        return $this->poules;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
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
