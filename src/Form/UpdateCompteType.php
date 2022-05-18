<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateCompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name' ,TextType::class,[
            
            'label' => 'Name ',
            'attr' => [
                'placeholder' => 'Merci de definir le nom',
                'classe' =>'name'
            ]
        ])
        ->add('tel' ,TextType::class,[
            'label' => 'Telephone',
            'attr' => [
                'placeholder' => 'Merci de definir le n°Tel',
                'classe' =>'tel'
            ]
        ])
        ->add('imagename',FileType::class,[
            'required'=> is_null($builder->getData()->getId())
        ],
          array('data_class' => null))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
