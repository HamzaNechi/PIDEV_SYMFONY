<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name' ,TextType::class,[
            'disabled'=> true ,
            'label' => 'Name ',
            'attr' => [
                'placeholder' => 'Merci de definir le nom',
                'classe' =>'name'
            ]
        ])
        ->add('email' ,EmailType::class,[
            'disabled'=> true ,
            'label' => 'Email ',
            'attr' => [
                'placeholder' => 'Merci de definir Email',
                'classe' =>'email'
            ]
        ])
        ->add('roles', ChoiceType::class, [
            'choices'  => [
                'Utilisateur' => "ROLE_USER",
                'Organisateur' => "ROLE_ORGANISATEUR",
                'Administrateur' => "ROLE_ADMIN",                   
            ],
            'expanded' => true,
            'multiple' => true,
            'label' => 'Roles'
 
        ])
        ->add('tel' ,TextType::class,[
            'disabled'=> true ,
            'label' => 'Telephone',
            'attr' => [
                'placeholder' => 'Merci de definir le n°Tel',
                'classe' =>'tel'
            ]
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
