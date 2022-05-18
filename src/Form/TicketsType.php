<?php

namespace App\Form;

use App\Entity\Tickets;
use App\Entity\User;
use App\Entity\Courses;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TicketsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('type',ChoiceType::class,array(
                'choices'=>array(
                    'VIRAGE GAUCHE'=>'VIRAGE GAUCHE',
                    'LOGUE'=>'LOGUE',
                    'VIRAGE DROIT'=>'VIRAGE DROIT',
                    'VIP'=>'VIP'
                )
            ))
            ->add('course',EntityType::class,[
                'class'=>Courses::class,
                'choice_label'=>'nom' 
                ])
            ->add('user',EntityType::class,[
                'class'=>User::class,
                'choice_label'=>'email'])
            ->add('Ajoute',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tickets::class,
        ]);
    }
}
