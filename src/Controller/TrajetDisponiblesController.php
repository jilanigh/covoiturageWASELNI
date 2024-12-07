<?php

namespace App\Controller;

use App\Repository\TrajetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrajetDisponiblesController extends AbstractController
{
    #[Route('/tdisponibles', name: 'app_trajet_disponibles')]
    public function index(TrajetRepository $trajetRepository): Response
    {
        $trajets = $trajetRepository->findAll(); // Fetch all available Trajet entities

        return $this->render('trajet_disponibles/index.html.twig', [
            'trajets' => $trajets,
        ]);
    }
}
