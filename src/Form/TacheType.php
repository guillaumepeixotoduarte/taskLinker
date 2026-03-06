<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Tache;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // On récupère l'objet Tache en cours
        $tache = $options['data'] ?? null;
        // On récupère le projet lié à cette tâche
        $projet = $tache ? $tache->getProjet() : null;

        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de la tâche',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('date', null, [
                'label' => 'Date',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'To Do' => 'A_FAIRE',
                    'Doing' => 'EN_COURS',
                    'Done' => 'TERMINE',
                ],
            ])
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choices' => $projet ? $projet->getEmployes()->toArray() : [],
                'placeholder' => '--- Aucun membre assigné ---',
                'required' => false,
                'choice_label' => function (Employe $employe) {
                    return $employe->getPrenom() . ' ' . $employe->getNom();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
        ]);
    }
}
