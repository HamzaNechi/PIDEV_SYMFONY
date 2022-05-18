<?php

namespace App\Form;

use App\Entity\Courses;
use App\Entity\Circuits;
use App\Entity\Saisons;
use App\Entity\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CoursesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entityManager = $options['entity_manager'];
        $rolesOrg = $entityManager->getRepository(User::class)
        ->findByRole();
        
        $builder
            ->add('nom')
            ->add('dateCourse',DateType::class,[
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            
            ->add('circuitid',EntityType::class,[
                'class'=>Circuits::class,
                'choice_label'=>'nom' ])
            ->add('organisateur',EntityType::class,[
                    'class'=>User::class,
                    'choices' =>$rolesOrg,
                    'choice_label'=>'name',
                     ])
            ->add('saison' ,EntityType::class,[
                'class'=>Saisons::class,
                'choice_label'=>'year' ])
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Courses::class,
        ])
        ->setRequired('entity_manager');
    }
}
