<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Poule;
use App\Form\PouleType;
use App\Repository\JoueurRepository;
use App\Repository\MatchRepository;
use App\Repository\PouleRepository;
use App\Repository\TournoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PouleController extends AbstractController
{

    /**
     * Lorsqu'une poule existe, on a un bouton pour ajouter des joueurs avec un champ pour donner le nombre de joueurs
     * en dessous. S'il y a suffisemment de joueurs, on les ajoute aléatoirement à la poule
     * @Route("/ajoutJoueurPoule/{id_poule}", name="ajoutJoueurPoule")
     */
    public function ajouterJoueur(EntityManagerInterface $manager, int $id_poule, JoueurRepository $joueurRepository, PouleRepository $pouleRepository, Request $request, MatchRepository $matchRepository)
    {
        // Les joueurs restants sont les joueurs qui n'ont pas de poule
        $poule = $pouleRepository->find($id_poule);
        $joueursRestants = $joueurRepository->getJoueursSansPoule($poule->getTournoi());
        if (sizeof($joueursRestants) == 0) {
            $request->getSession()->getFlashBag()->add('error', "Il n'y a plus de joueurs à ajouter, veuillez en inscrire d'autres pour pouvoir les ajouter aux poules");

            return $this->redirectToRoute('tournoi_detail', [
                'id' => $poule->getTournoi()->getId(),
                'finPoules' => false,
                'creationEnCours' => false,
                'creationJoueurEnCours' => false,
                'joueursSansPoule' => $joueursRestants,
                'matchRepository' => $matchRepository,
            ]);
        }
        $joueurAAjouter = $this->joueurAleatoire($joueursRestants);
        $poule = $pouleRepository->find($id_poule);
        $joueurAAjouter->setPoule($poule);

        $manager->persist($joueurAAjouter);
        $manager->flush();

        $joueursRestants = $joueurRepository->getJoueursSansPoule($poule->getTournoi());

        return $this->render('tournoi/detail.html.twig', [
            'tournoi' => $poule->getTournoi(),
            'creationEnCours' => false,
            'finPoules' => false,
            'creationJoueurEnCours' => false,
            'joueursSansPoule' => $joueursRestants,
            'matchRepository' => $matchRepository,
        ]);
    }

    /**
     * @param array $joueursRestants
     * @param int $nombreJoueurs
     * renvoi une liste de $nombreJoueurs aléatoire parmi les joueurs restants
     * @return Joueur
     */
    private function joueurAleatoire($joueursRestants)
    {

        try {
            $indiceAleatoire = random_int(0, sizeof($joueursRestants) - 1);
            $joueur = $joueursRestants[$indiceAleatoire];
        } catch (\Exception $e) {
        }

        return $joueur;
    }

    /**
     * @Route("/creationPoule/{id_tournoi}", name="creationPoule")
     */
    public function creerPoule(MatchRepository $matchRepository, PouleRepository $pouleRepository, EntityManagerInterface $manager, Request $request, $id_tournoi, TournoiRepository $tournoiRepository, JoueurRepository $joueurRepository)
    {

        $poule = new Poule();
        // on crée ensuite le formulaire
        $form = $this->createForm(PouleType::class, $poule);
        $form->handleRequest($request);
        $tournoi = $tournoiRepository->find($id_tournoi);
        $joueursRestants = $joueurRepository->getJoueursSansPoule($tournoi);

        if ($form->isSubmitted() and $form->isValid()) {
            $poule->setTournoi($tournoi);
            $manager->persist($poule);
            $manager->flush();

            return $this->redirectToRoute('tournoi_detail', [
                'id' => $poule->getTournoi()->getId(),
                'creationEnCours' => false,
                'finPoules' => false,
                'creationJoueurEnCours' => false,
                'joueursSansPoule' => $joueursRestants,
                'matchRepository' => $matchRepository,
            ]);
        }

        return $this->render('tournoi/detail.html.twig', [
            'form' => $form->createView(),
            'tournoi' => $tournoi,
            'creationEnCours' => true,
            'creationJoueurEnCours' => false,
            'joueursSansPoule' => $joueursRestants,
            'matchRepository' => $matchRepository,
        ]);
    }

    /**
     * @Route("/suppressionPoule/{id}", name="suppressionPoule")
     */
    public function supprimerPoule($id, EntityManagerInterface $manager, PouleRepository $pouleRepository, JoueurRepository $joueurRepository, MatchRepository $matchRepository)
    {
        // S'il existe des joueurs dans cette poule, on passe leur poule à null
        $poule = $pouleRepository->find($id);
        $joueursDeLaPoule = $joueurRepository->findByPoule($poule);


        foreach ($joueursDeLaPoule as $joueur) {
            $joueur->setPoule(null);
            $manager->persist($joueur);
        }

        $manager->remove($poule);

        $manager->flush();

        $joueursRestants = $joueurRepository->getJoueursSansPoule($poule->getTournoi());

        return $this->redirectToRoute('tournoi_detail', [
            'id' => $poule->getTournoi()->getId(),
            'creationEnCours' => false,
            'finPoules' => false,
            'creationJoueurEnCours' => false,
            'joueursSansPoule' => $joueursRestants,
            'matchRepository' => $matchRepository,
        ]);
    }

    /**
     * @Route("/finPoules/{id_tournoi}", name="finPoules")
     */
    public function finDesPoules($id_tournoi, MatchRepository $matchRepository, TournoiRepository $tournoiRepository)
    {
        $tournoi = $tournoiRepository->find($id_tournoi);
        return $this->render('tournoi/detail.html.twig', [
            'tournoi' => $tournoi,
            'finPoules' => true,
            'creationEnCours' => false,
            'creationJoueurEnCours' => false,
            'joueursSansPoule' => null,
            'matchRepository' => $matchRepository,
        ]);
    }
}
