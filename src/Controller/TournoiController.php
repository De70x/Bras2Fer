<?php

namespace App\Controller;

use App\Entity\Tournoi;
use App\Form\TournoiType;
use App\Repository\JoueurRepository;
use App\Repository\MatchRepository;
use App\Repository\TournoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TournoiController extends AbstractController
{
    /**
     * @Route("/tournoi/{id}", name="tournoi_detail")
     */
    public function detailTournoi($id, TournoiRepository $tournoiRepository, JoueurRepository $joueurRepository, MatchRepository $matchRepository)
    {
        $tournoi = $tournoiRepository->find($id);
        $joueursSansPoule = $joueurRepository->getJoueursSansPoule($tournoi);
        return $this->render('tournoi/detail.html.twig', [
            'tournoi' => $tournoi,
            'creationEnCours' => false,
            'creationJoueurEnCours' => false,
            'joueursSansPoule' => $joueursSansPoule,
            'matchRepository' => $matchRepository,
        ]);
    }

    /**
     * @Route("/creationTournoi", name="creationTournoi")
     */
    public function creerTournoi(EntityManagerInterface $manager, Request $request)
    {
        $tournoi = new Tournoi();
        // on crée ensuite le formulaire
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $tournoi->setDate(new \DateTime());
            $manager->persist($tournoi);
            $manager->flush();

            return $this->redirectToRoute('tournoi_detail', [
                'id' => $tournoi->getId(),
                'creationEnCours' => false,
                'creationJoueurEnCours' => false,
            ]);
        }

        return $this->render('tournoi/creer_tournoi.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tournoi/supprimer/{id}", name="suppressionTournoi")
     */
    public function deleteIdea($id, EntityManagerInterface $manager, TournoiRepository $tournoiRepository)
    {
        $tournoi = $tournoiRepository->find($id);

        $manager->remove($tournoi);
        $manager->flush();

        return $this->redirectToRoute('accueil', [

        ]);
    }
}
