<?php

namespace App\Form;

use App\Entity\Equipes;
use App\Entity\Membres;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('image',FileType::class, array('data_class' => null), [
                'mapped' => false,
                'required'=>false,
                ])
            ->add('role',ChoiceType::class,[
                'choices'=>[
                    "Chef d'équipe"=>"Chef d'équipe",
                    "Pilote"=>"Pilote",
                    "Mecanicien"=>"Mecanicien"
                ],

                'attr'=>[
                    'id'=>'role'
                ]
            ])
            ->add('nationalite')
            ->add('date_naissance',DateType::class,[
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('equipe',EntityType::class,[
                'class'=>Equipes::class,
                'choice_label'=>'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membres::class,
        ]);
    }
}
