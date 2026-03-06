<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Tache;
use App\Form\TacheType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TacheController extends AbstractController
{
    #[Route('/tache/new/{projetId}', name: 'app_tache_add')]
    public function add(int $projetId, Request $request, EntityManagerInterface $em): Response
    {
        $projet = $em->getRepository(Projet::class)->find($projetId);
        $tache = new Tache();
        $tache->setProjet($projet); // Associez la tâche au projet
        
        $form = $this->createForm(TacheType::class, $tache);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tache);
            $em->flush();
            $this->addFlash('success', 'La tâche a bien été créée');
            return $this->redirectToRoute('app_projet_details', [
                'id' => $projetId,
            ]);
        }

        return $this->render('tache/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tache/edit/{id}', name: 'app_tache_edit', methods: ['GET', 'POST'])]
    public function edit(Tache $tache, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($tache);
            $entityManager->flush();

            $this->addFlash('success', 'Tâche mise à jour !');

            return $this->redirectToRoute('app_projet_details', ['id' => $tache->getProjet()->getId()]);
        }

        return $this->render('tache/add.html.twig', [
            'tache' => $tache,
            'form' => $form,
        ]);
    }

    #[Route('/tache/delete/{id}', name: 'app_tache_delete', methods: ['POST'])]
    public function delete(Request $request, Tache $tache, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tache->getId(), $request->request->get('_token'))) {
            $em->remove($tache);
            $em->flush();
            $this->addFlash('success', 'La tâche a bien été supprimée');
        }

        return $this->redirectToRoute('app_projet_details', [
            'id' => $tache->getProjet()->getId(),
        ]);
    }
}
