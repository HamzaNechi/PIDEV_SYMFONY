<?php

namespace App\Form;
use App\Entity\Pilotes;
use App\Entity\Qualifying;
use App\Entity\Courses;
use App\Entity\Equipes;
use App\Entity\Participation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('position')
            ->add('points')
            ->add('pilote', EntityType::class,[
                'class'=>Pilotes::class,
                'choice_label' => 'numero',
            ])
            ->add('equipe', EntityType::class,[
                'class'=>Equipes::class,
                'choice_label' => 'nom',
            ])
            ->add('course', EntityType::class,[
                'class'=>Courses::class,
                'choice_label' => 'nom',
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
        ]);
    }
}
