<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'Friend' => "friend",
                    'Photo' => "photo",
                    'Video' => "video",
                ),
                'expanded' => true,
                'multiple' => false,
            ))
            ->add('save', SubmitType::class, array('label' => 'Get data'))
        ;
    }
//
//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            'data_class' => Category::class,
//        ]);
//    }
}
