<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Intérim' => 'Intérim',
                    'Stage' => 'Stage',
                    'Freelance' => 'Freelance',
                ],
            ])
            ->add('dateEntree', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'entrée',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Employe' => 'ROLE_USER',
                    'Chef de Projet' => 'ROLE_CHEF_PROJET',
                ],
                'expanded' => false, // false = liste déroulante, true = boutons radio
                'multiple' => false, // On ne veut qu'un seul choix
                'label' => 'Rôle',
                'attr' => ['class' => 'form-control'] // Tes classes CSS
            ]);
        ;

        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(
            function ($rolesArray) {
                // Transforme le tableau en chaîne pour l'affichage dans le select
                return count($rolesArray) > 0 ? $rolesArray[0] : null;
            },
            function ($rolesString) {
                // Transforme la chaîne du select en tableau pour l'entité
                return [$rolesString];
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
            'is_edit' => false,
        ]);
    }
}
