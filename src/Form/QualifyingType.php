<?php

namespace App\Form;

use App\Entity\Pilotes;
use App\Entity\Qualifying;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;


class QualifyingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('q1', TextType::class, [

                'attr' => ['placeholder' => 'mm:ss.xxx']
            ])
            ->add('q2', TextType::class, [

                'attr' => ['placeholder' => 'mm:ss.xxx']
            ])
            ->add('q3', TextType::class, [

                'attr' => ['placeholder' => 'mm:ss.xxx']
            ])
            ->add(
                'position'
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Qualifying::class,
        ]);
    }
}
