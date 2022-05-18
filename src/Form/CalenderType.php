<?php

namespace App\Form;

use App\Entity\Calender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Entity\Courses;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CalenderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('start', DateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('end', DateTimeType::class, [
                'date_widget' => 'single_text'
            ])
            ->add('background_color', ColorType::class)
            ->add('border_color', ColorType::class)
            ->add('text_color', ColorType::class)
            ->add('course' ,EntityType::class,[
                'class'=>Courses::class,
                'choice_label'=>'nom' ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calender::class,
        ]);
    }
}