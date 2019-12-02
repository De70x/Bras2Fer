<?php

namespace App\Controller;

use App\Repository\TournoiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(TournoiRepository $tournoiRepository)
    {
        $liste_tournois = $tournoiRepository->findAll();
            return $this->render('accueil/index.html.twig', [
                'tournois' => $liste_tournois,
            ]);
    }
}
