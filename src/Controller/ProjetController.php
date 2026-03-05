<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjetController extends AbstractController
{
    #[Route('/projet', name: 'app_projet')]
    #[Route('/', name: 'app_projet_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $projets = $em->getRepository(Projet::class)->findAll();


        return $this->render('projet/index.html.twig', [
            'title' => 'Projets',
            'projets' => $projets,
        ]);
    }

    #[Route('/projet/add', name: 'app_projet_add')]
    #[Route('/projet/edit/{id}', name: 'app_projet_edit')]
    public function add(?Projet $projet = null,Request $request, EntityManagerInterface $em): Response
    {
        if(!$projet){
            $projet = new Projet();
        }
        
        $form = $this->createForm(ProjetType::class, $projet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projet);
            $em->flush();
            $this->addFlash('success', 'Le projet a bien été créé');
            return $this->redirectToRoute('app_projet_index');
            
        }

        return $this->render('projet/add.html.twig', [
            'form' => $form->createView(),
            'projet' => $projet,
        ]);
    }

    #[Route('/projet/{id}', name: 'app_projet_details')]
    public function details(Projet $projet): Response
    {
        return $this->render('projet/details.html.twig', [
            'projet' => $projet,
        ]);
    }

    #[Route('/projet/delete/{id}', name: 'app_projet_delete', methods: ['POST'])]
    public function delete(Request $request, Projet $projet, EntityManagerInterface $em): Response
    {    
        if ($this->isCsrfTokenValid('delete' . $projet->getId(), $request->request->get('_token'))) {
            $em->remove($projet);
            $em->flush();
            $this->addFlash('success', 'Projet supprimé avec succès.');   
        }

        return $this->redirectToRoute('app_projet_index');

    }
}
