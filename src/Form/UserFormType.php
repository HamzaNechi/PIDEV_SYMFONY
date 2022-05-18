<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name' ,TextType::class,[
                'attr' => [
                    'placeholder' => 'Merci de definir le nom',
                    'classe' =>'name'
                ]
            ])
            ->add('email' ,EmailType::class,[
                'attr' => [
                    'placeholder' => 'Merci de definir Adresse Email',
                    'classe' =>'name'
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
            ->add('password', PasswordType::class,[
                'attr' => [
                    'placeholder' => 'Merci de definir le password',
                    'classe' =>'name',
                ],
                'constraints' => [
            ],
            'empty_data' => ''
            ])
            ->add('tel' ,TextType::class,[
                'attr' => [
                    'placeholder' => 'Merci de definir le num de telephone',
                    'classe' =>'name'
                ]
            ])
            ->add('imagename',FileType::class, array('data_class' => null))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
