<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('prenom', TextType::class, ['label' => 'Prénom'])
            ->add('email')
            ->add('date_entree', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'entrée',
                'data' => new \DateTime(), // Par défaut aujourd'hui
            ])
            ->add('statut', ChoiceType::class, [
                'choices'  => [
                    'Actif' => 'ACTIF',
                    'En congé' => 'CONGE',
                    'Sortie' => 'SORTIE',
                ],
            ])
            // On ajoute le choix du rôle (en mode "simple" pour le formulaire)
            ->add('isChefProjet', ChoiceType::class, [
                'mapped' => false, // Ce champ n'existe pas dans l'entité, on le gèrera dans le contrôleur
                'label' => 'Type de compte',
                'choices'  => [
                    'Employé classique' => 'USER',
                    'Chef de Projet' => 'CHEF',
                ],
                'expanded' => true, // Boutons radio au lieu de liste déroulante
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [new IsTrue(['message' => 'Vous devez accepter les conditions.'])],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(['message' => 'Entrez un mot de passe']),
                    new Length(['min' => 6, 'max' => 4096]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
