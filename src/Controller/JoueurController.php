<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use App\Repository\MatchRepository;
use App\Repository\TournoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JoueurController extends AbstractController
{
    /**
     * @Route("/creationJoueur/{id_tournoi}", name="creationJoueur")
     */
    public function creerPoule(EntityManagerInterface $manager, Request $request, $id_tournoi, TournoiRepository $tournoiRepository, JoueurRepository $joueurRepository, MatchRepository $matchRepository)
    {

        $joueur = new Joueur();
        // on crée ensuite le formulaire
        $formJoueur = $this->createForm(JoueurType::class, $joueur);
        $formJoueur->handleRequest($request);
        $tournoi = $tournoiRepository->find($id_tournoi);


        if ($formJoueur->isSubmitted() and $formJoueur->isValid()) {
            $joueur->setTournoi($tournoi);
            $joueur->setPoule(null);
            $manager->persist($joueur);
            $manager->flush();

            $joueursRestants = $joueurRepository->getJoueursSansPoule($tournoi);
            return $this->redirectToRoute('tournoi_detail', [
                'id' => $id_tournoi,
                'creationEnCours' => false,
                'finPoules' => false,
                'creationJoueurEnCours' => false,
                'joueursSansPoule' => $joueursRestants,
                'matchRepository' => $matchRepository,
            ]);
        }

        $joueursRestants = $joueurRepository->getJoueursSansPoule($tournoi);
        return $this->render('tournoi/detail.html.twig', [
            'formJoueur' => $formJoueur->createView(),
            'tournoi' => $tournoi,
            'creationEnCours' => false,
            'finPoules' => false,
            'creationJoueurEnCours' => true,
            'joueursSansPoule' => $joueursRestants,
            'matchRepository' => $matchRepository,
        ]);
    }
}
