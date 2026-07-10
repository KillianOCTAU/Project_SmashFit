<?php

namespace App\Controller;

use App\Entity\Materiel;
use App\Repository\MarqueRepository;
use App\Repository\MaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/materiels')]
class MaterielController extends AbstractController
{
    #[Route('', name: 'app_materiel_index')]
    public function index(
        Request $request,
        MaterielRepository $materielRepo,
        MarqueRepository $marqueRepo
    ): Response {
        $criteres = array_filter([
            'type'        => $request->query->get('type', ''),
            'niveau'      => $request->query->get('niveau', ''),
            'flexibilite' => $request->query->get('flexibilite', ''),
            'equilibre'   => $request->query->get('equilibre', ''),
            'marque'      => $request->query->get('marque', ''),
            'search'      => $request->query->get('search', ''),
        ]);

        $materiels = $materielRepo->findWithFilters($criteres);

        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'count' => count($materiels),
                'html'  => $this->renderView('materiel/_cards.html.twig', [
                    'materiels' => $materiels,
                ]),
            ]);
        }

        return $this->render('materiel/index.html.twig', [
            'materiels'    => $materiels,
            'marques'      => $marqueRepo->findAllOrdered(),
            'criteres'     => array_merge([
                'type' => '', 'niveau' => '', 'flexibilite' => '',
                'equilibre' => '', 'marque' => '', 'search' => '',
            ], $criteres),
            'types'        => Materiel::TYPES,
            'niveaux'      => Materiel::NIVEAUX,
            'flexibilites' => Materiel::FLEXIBILITES,
            'equilibres'   => Materiel::EQUILIBRES,
        ]);
    }

    #[Route('/{id}', name: 'app_materiel_show', requirements: ['id' => '\d+'])]
    public function show(Materiel $materiel): Response
    {
        return $this->render('materiel/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }
}