<?php

namespace App\Controller;

use App\Repository\RecommandationRepository;
use App\Service\RecommandationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/recommandations')]
class RecommandationController extends AbstractController
{
    #[Route('', name: 'app_recommandation_index')]
    public function index(RecommandationRepository $repo): Response
    {
        $user   = $this->getUser();
        $profil = $user->getProfil();

        if (!$profil || !$profil->isComplete()) {
            $this->addFlash('warning', 'Complétez votre profil pour obtenir des recommandations.');
            return $this->redirectToRoute('app_profil_edit');
        }

        $recos = $repo->findByUtilisateurOrderedByScore($user);

        if (empty($recos)) {
            return $this->redirectToRoute('app_recommandation_generate');
        }

        return $this->render('recommandation/index.html.twig', [
            'recommandations' => $recos,
            'profil'          => $profil,
        ]);
    }

    #[Route('/generer', name: 'app_recommandation_generate')]
    public function generate(RecommandationService $service): Response
    {
        $user  = $this->getUser();
        $recos = $service->genererPourUtilisateur($user);

        if (empty($recos)) {
            $this->addFlash('warning', 'Aucune recommandation. Vérifiez votre profil.');
            return $this->redirectToRoute('app_profil_edit');
        }

        $this->addFlash('success', count($recos) . ' recommandation(s) générée(s) !');
        return $this->redirectToRoute('app_recommandation_index');
    }
}