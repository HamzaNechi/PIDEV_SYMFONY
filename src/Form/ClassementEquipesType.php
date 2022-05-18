<?php

namespace App\Form;

use App\Entity\ClassementEquipes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ClassementEquipesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('equipesEquipeId')
            ->add('pointsTotal')
            ->add('position')
            ->add('saisonsYear', EntityType::class,['class' => 'App\Entity\Saisons'::class, 'choice_label' => 'year'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClassementEquipes::class,
        ]);
    }
}
