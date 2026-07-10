<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Entity\Materiel;
use App\Form\MarqueType;
use App\Form\MaterielType;
use App\Repository\MarqueRepository;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin_dashboard')]
    public function dashboard(
        MaterielRepository $matRepo,
        MarqueRepository $marqueRepo
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'nbMateriels' => count($matRepo->findAllAvailable()),
            'nbMarques'   => count($marqueRepo->findAllOrdered()),
        ]);
    }

    // ── Marques ──

    #[Route('/marques', name: 'app_admin_marques')]
    public function marques(MarqueRepository $repo): Response
    {
        return $this->render('admin/marques/index.html.twig', [
            'marques' => $repo->findAllOrdered(),
        ]);
    }

    #[Route('/marques/nouvelle', name: 'app_admin_marque_new')]
    public function newMarque(Request $request, EntityManagerInterface $em): Response
    {
        $marque = new Marque();
        $form   = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($marque);
            $em->flush();
            $this->addFlash('success', 'Marque ajoutée !');
            return $this->redirectToRoute('app_admin_marques');
        }

        return $this->render('admin/marques/form.html.twig', [
            'form'   => $form,
            'marque' => $marque,
        ]);
    }

    #[Route('/marques/{id}/supprimer', name: 'app_admin_marque_delete', methods: ['POST'])]
    public function deleteMarque(Marque $marque, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_marque_'.$marque->getId(), $request->request->get('_token'))) {
            $em->remove($marque);
            $em->flush();
            $this->addFlash('success', 'Marque supprimée.');
        }
        return $this->redirectToRoute('app_admin_marques');
    }

    // ── Matériels ──

    #[Route('/materiels', name: 'app_admin_materiels')]
    public function materiels(MaterielRepository $repo): Response
    {
        return $this->render('admin/materiels/index.html.twig', [
            'materiels' => $repo->findAllAvailable(),
        ]);
    }

    #[Route('/materiels/nouveau', name: 'app_admin_materiel_new')]
    public function newMateriel(Request $request, EntityManagerInterface $em): Response
    {
        $materiel = new Materiel();
        $form     = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($materiel);
            $em->flush();
            $this->addFlash('success', 'Produit ajouté !');
            return $this->redirectToRoute('app_admin_materiels');
        }

        return $this->render('admin/materiels/form.html.twig', [
            'form'     => $form,
            'materiel' => $materiel,
        ]);
    }

    #[Route('/materiels/{id}/modifier', name: 'app_admin_materiel_edit', requirements: ['id' => '\d+'])]
    public function editMateriel(Materiel $materiel, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Produit modifié !');
            return $this->redirectToRoute('app_admin_materiels');
        }

        return $this->render('admin/materiels/form.html.twig', [
            'form'     => $form,
            'materiel' => $materiel,
        ]);
    }

    #[Route('/materiels/{id}/supprimer', name: 'app_admin_materiel_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function deleteMateriel(Materiel $materiel, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_mat_'.$materiel->getId(), $request->request->get('_token'))) {
            $materiel->setDisponible(false);
            $em->flush();
            $this->addFlash('success', 'Produit supprimé.');
        }
        return $this->redirectToRoute('app_admin_materiels');
    }
}