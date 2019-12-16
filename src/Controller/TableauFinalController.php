<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Repository\JoueurRepository;
use App\Repository\TournoiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TableauFinalController extends AbstractController
{
    private $principale = array();
    private $consolante = array();

    /**
     * @Route("/tableauFinal/{id}", name="tableauFinal")
     */
    public function preparationTableaux($id, TournoiRepository $tournoiRepository, JoueurRepository $joueurRepository)
    {
        $this->remplirTableau($id, $tournoiRepository, $joueurRepository);

        // S'il manque des joueurs pour atteindre une puissance de 2 on rajoute des fantomes pour les deux tableaux
        $puissancePrincipale = $this->puissance2($this->principale);
        while(sizeof($this->principale) < pow(2, $puissancePrincipale)){
            array_push($this->principale, $this->newJoueurFantome());
        }
        $puissanceConsolante = $this->puissance2($this->consolante);
        while(sizeof($this->consolante) < pow(2, $puissanceConsolante)){
            array_push($this->consolante, $this->newJoueurFantome());
        }

        return $this->render('tableau_final\tableau_final.html.twig', [
            'principale' => $this->principale,
            'nbIterationsPrincipale' => $puissancePrincipale,
            'consolante' => $this->consolante,
            'nbIterationsConsolante' => $puissanceConsolante,
        ]);
    }

    public function remplirTableau($id, TournoiRepository $tournoiRepository, JoueurRepository $joueurRepository){
        $tournoi = $tournoiRepository->find($id);
        $joueurs = $joueurRepository->findByTournoi($tournoi);

        // On a uniquement ceux qui sont cochés. il faut donc récupérer ces joueurs et
        // ajouter le reste à l'autre tableau.
        foreach ($_POST as $key => $value){
            $joueur = $joueurRepository->find($key);
            array_push($this->principale, $joueur);
            unset($joueurs[array_search($joueur,$joueurs)]);
        }

        $this->consolante = array_values($joueurs);

        usort($this->consolante, array("App\\Entity\\Joueur", "comparator"));
        usort($this->principale, array("App\\Entity\\Joueur", "comparator"));
    }


    /**
     * Cette méthode sert à renvoyer la puissance de deux supérieure ou égale au nombre de joueurs du tableau
     * @param $nbJoueurs
     * @return mixed
     */
    public function puissance2($tableau)
    {
        $nbJoueurs = sizeof($tableau);
        $i = 0;

        while($nbJoueurs > pow(2, $i)){
            $i++;
        }

        return $i;
    }

    public function newJoueurFantome(){
        $joueur = new Joueur();
        $joueur->setNom('Fantome');
        $joueur->setId(-1);
        return $joueur;
    }
}
