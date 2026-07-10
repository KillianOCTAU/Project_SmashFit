<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Form\ProfilType;
use App\Service\BwfApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/profil')]
class ProfilController extends AbstractController
{
    #[Route('', name: 'app_profil')]
    public function index(): Response
    {
        $user   = $this->getUser();
        $profil = $user->getProfil() ?? new Profil();

        return $this->render('profil/index.html.twig', [
            'user'   => $user,
            'profil' => $profil,
        ]);
    }

    #[Route('/modifier', name: 'app_profil_edit')]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user   = $this->getUser();
        $profil = $user->getProfil();

        if (!$profil) {
            $profil = new Profil();
            $profil->setUtilisateur($user);
            $em->persist($profil);
        }

        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Profil mis à jour !');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('profil/edit.html.twig', [
            'form'   => $form,
            'profil' => $profil,
        ]);
    }

    #[Route('/clubs/search', name: 'app_profil_clubs_search')]
    public function searchClubs(Request $request, BwfApiService $bwf): JsonResponse
    {
        $query = $request->query->get('q', '');
        return $this->json($bwf->rechercherClubs($query));
    }

    #[Route('/licence/verify', name: 'app_profil_licence_verify', methods: ['POST'])]
    public function verifyLicence(Request $request, BwfApiService $bwf): JsonResponse
    {
        $numero = $request->request->get('numero', '');
        if (empty($numero)) {
            return $this->json(['valide' => false, 'message' => 'Numéro vide.']);
        }
        return $this->json($bwf->verifierLicence($numero));
    }
}