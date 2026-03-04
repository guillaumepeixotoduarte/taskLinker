<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Employe;
use App\Form\EmployeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class EmployeController extends AbstractController
{
    #[Route('/employe/add', name: 'app_employe_add')]
    #[Route('/employe/edit/{id}', name: 'app_employe_edit')]
    public function index(?Employe $employe = null,Request $request, EntityManagerInterface $em): Response
    {
        if(!$employe){
            $employe = new Employe();
        }
        
        $form = $this->createForm(EmployeType::class, $employe, [
            'is_edit' => $employe->getId() !== null,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $employe = $employe->setPassword(password_hash($employe->getPassword(), PASSWORD_BCRYPT));
            $employe->setRoles($employe->isChef() ? ['ROLE_CHEF'] : ['ROLE_EMPLOYE']);
            $em->persist($employe);
            $em->flush();
            $this->addFlash('success', 'L\'employé a bien été créé');
            return $this->redirectToRoute('app_employe_list');
            
        }

        return $this->render('employe/index.html.twig', [
            'controller_name' => 'EmployeController',
            'form' => $form->createView(),
            'employe' => $employe,
        ]);
    }

    #[Route('/employe', name: 'app_employe_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $employes = $em->getRepository(Employe::class)->findAll();
        return $this->render('employe/list.html.twig', [
            'employes' => $employes,
        ]);
    }

    #[Route('/employe/delete/{id}', name: 'app_employe_delete', methods: ['POST'])]
    public function delete(Request $request, Employe $employe, EntityManagerInterface $em): Response
    {
    if ($this->isCsrfTokenValid('delete' . $employe->getId(), $request->request->get('_token'))) {
        $em->remove($employe);
        $em->flush();
        $this->addFlash('success', 'Employé supprimé avec succès.');
    }

    return $this->redirectToRoute('app_employe_list');
}
}
