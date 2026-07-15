<?php

namespace App\Controller;

use App\Repository\MaterielRepository;
use App\Service\BwfApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MaterielRepository $repo, BwfApiService $bwf): Response
    {
        return $this->render('home/index.html.twig', [
            'stats' => $repo->countByType(),
            'news'  => $bwf->getActualites(3),
        ]);
    }

    #[Route('/informations', name: 'app_informations')]
    public function informations(): Response
    {
        return $this->render('home/informations.html.twig');
    }
}