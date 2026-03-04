<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
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
            ->add('contrat', ChoiceType::class, [
                'label' => 'Contrat',
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Intérim' => 'Intérim',
                    'Stage' => 'Stage',
                ],
            ])
            ->add('dateArrive', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'arrivée',
            ])
        ;

        if(!$options['is_edit']){
            $builder
                ->add('password', PasswordType::class, [
                    'label' => 'Mot de passe',
                ])
                ->add('isChef', CheckboxType::class, [
                    'label' => 'Chef',
                    'required' => false,
                ])
                ->add('compteActif', CheckboxType::class, [
                    'label' => 'Compte Actif',
                    'required' => false,
                ]);
                
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
            'is_edit' => false,
        ]);
    }
}
