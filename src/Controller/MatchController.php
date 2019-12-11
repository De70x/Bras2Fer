<?php

namespace App\Controller;

use App\Entity\Match;
use App\Form\MatchType;
use App\Repository\JoueurRepository;
use App\Repository\MatchRepository;
use App\Repository\PouleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    /**
     * @Route("/creationMatch/{id_poule}", name="creationMatch")
     */
    public function creerMatch(PouleRepository $pouleRepository, $id_poule, EntityManagerInterface $manager, Request $request, JoueurRepository $joueurRepository, MatchRepository $matchRepository)
    {

        $match = new Match();
        $poule = $pouleRepository->find($id_poule);
        // on crée ensuite le formulaire
        $options = array('poule' => $poule);
        $form = $this->createForm(MatchType::class, $match, $options);
        $form->handleRequest($request);


        if ($form->isSubmitted() and $form->isValid()) {
            $match->setPoule($poule);
            $joueur1 = $form['joueur1']->getData();
            $joueur2 = $form['joueur2']->getData();
            $isMatchBonus = $form['matchBonus']->getData();

            if($joueur1 == $joueur2){
                $request->getSession()->getFlashBag()->add('error', "Vous voulez faire comment ? bras droit contre bras gauche ?");

                return $this->redirectToRoute('creationMatch', [
                    'id_poule' => $poule->getId(),
                ]);
            }

            if ($matchRepository->findMatchParJoueurs($joueur1, $joueur2) > 0) {
                if ($matchRepository->findMatchParJoueurs($joueur2,$joueur1) > 0 && !$isMatchBonus) {
                    $request->getSession()->getFlashBag()->add('error', "Ces deux joueurs ont déjà fait le match aller et le match retour !");

                    return $this->redirectToRoute('creationMatch', [
                        'id_poule' => $poule->getId(),
                    ]);
                }
            }

            if ($form['joueur1Gagne']->isClicked()) {
                $match->setGagnant($joueur1);
            }
            if ($form['joueur2Gagne']->isClicked()) {
                $match->setGagnant($joueur2);
            }


            $manager->persist($match);
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

        return $this->render('match/match.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
