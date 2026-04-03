<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        AppCustomAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        GoogleAuthenticatorInterface $googleAuthenticator
    ): Response {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_projet');
        }

        $user = new Employe();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView(),
            ], new Response(null, Response::HTTP_UNPROCESSABLE_ENTITY));

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

            $secret = $googleAuthenticator->generateSecret();
            $user->setGoogleAuthenticatorSecret($secret);

            // Note : Les champs 'nom', 'prenom', 'email', 'date_entree' et 'statut'
            // sont automatiquement remplis dans $user par le $form->handleRequest($request)
            // car ils sont "mappés" par défaut dans le RegistrationFormType.

            $entityManager->persist($user);
            $entityManager->flush();

            $request->getSession()->set('registration_email', $user->getEmail());
            return $this->redirectToRoute('app_register_success');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register/success', name: 'app_register_success')]
    public function success(Request $request, GoogleAuthenticatorInterface $googleAuthenticator, EntityManagerInterface $em): Response
    {
        $email = $request->getSession()->get('registration_email');
        if (!$email) return $this->redirectToRoute('app_register');

        $user = $em->getRepository(Employe::class)->findOneBy(['email' => $email]);
        $qrCodeUrl = $googleAuthenticator->getQRContent($user);

        $qrCode = new QrCode(
            data: $qrCodeUrl,
            encoding: new Encoding('UTF-8'),
        );

        // 2. On utilise le Writer pour générer l'image
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // 3. On génère la Data URI pour Twig
        $qrCodeDataUri = $result->getDataUri();


        return $this->render('registration/success.html.twig', [
            'qrCodeDataUri' => $qrCodeDataUri,
            'secret' => $user->getGoogleAuthenticatorSecret()
        ]);
    }

}
