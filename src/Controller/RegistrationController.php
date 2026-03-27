<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        AppCustomAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ): Response {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_projet');
        }

        $user = new Employe();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. On récupère les données du champ non-mappé (le rôle)
            $user->setRoles(['ROLE_USER']);
            $user->setDateEntree(new \DateTime()); // Par défaut on mettra la date de création du compte comme date d'entrée
            $user->setStatut(""); // Aucune valeur par défaut

            // 2. On hache le mot de passe (champ non-mappé plainPassword)
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Note : Les champs 'nom', 'prenom', 'email', 'date_entree' et 'statut'
            // sont automatiquement remplis dans $user par le $form->handleRequest($request)
            // car ils sont "mappés" par défaut dans le RegistrationFormType.

            $entityManager->persist($user);
            $entityManager->flush();

            // 3. Authentification automatique après l'inscription
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
