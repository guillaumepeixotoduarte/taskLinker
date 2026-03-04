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
            'controller_name' => 'Projets',
            'projets' => $projets,
        ]);
    }

    #[Route('/projet/add', name: 'app_projet_add')]
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
            'controller_name' => 'ProjetController',
            'form' => $form->createView(),
            'projet' => $projet,
        ]);
    }
}
