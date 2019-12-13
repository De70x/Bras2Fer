<?php

namespace App\Controller;

use App\Repository\TournoiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TableauFinalController extends AbstractController
{
    /**
     * @Route("/tableauFinal/{id}", name="tableauFinal")
     */
    public function generationTableaux($id, TournoiRepository $tournoiRepository)
    {
        $tournoi = $tournoiRepository->find($id);
        $joueurs = $tournoi->getJoueurs();
        $principale = array();
        $consolante = array();
        // On a uniquement ceux qui sont cochés. il faut donc récupérer ces joueurs et
        // ajouter le reste à l'autre tableau.
        foreach ($_POST as $key => $value){
            var_dump($key.$value);
        }

        return $this->render('tableau_final\tableau_final.html.twig', [
            'principale' => $principale,
            'consolante' => $consolante,
        ]);
    }


    public function determinerTypeTableau($nbJoueurs)
    {
        // On est généreux, on imagine qu'un jour il y aura 128 personnes dans le tableau final
        $puiss2 = [1, 2, 4, 8, 16, 32, 64, 128];

        // On parcourt les puissance de 2
        for ($i = 0; $i < sizeof($puiss2); $i++) {
            // Si par chance on tombe sur un nombre de joueur qui vaut une puissance de 2
            // on retourne cette puissance
            if ($nbJoueurs == $puiss2[$i]) {
                return $puiss2[$i];
            } // Sinon, on vérifie quelle puissance de 2 est la plus proche (celle au dessus ou celle en dessous)
            elseif ($nbJoueurs > 4 && $nbJoueurs < $puiss2[$i]) {
                if ($nbJoueurs >= $puiss2[$i - 1] + $puiss2[$i - 2]) {
                    return $puiss2[$i];
                } else {
                    return $puiss2[$i - 1];
                }
            }
        }
    }
}
