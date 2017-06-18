<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
class EditCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Название категории',
                'label_attr' => array('class' => 'col-xs-12 col-md-4'),
                'attr'       => array('class' => 'col-xs-12 col-md-6'),))
            ->add('parent', TextType::class, array(
                'label' => 'Родительская категория',
                'label_attr' => array('class' => 'col-xs-12 col-md-4'),
                'attr' => array('class' => 'categoryField col-xs-12 col-md-6')));
    }
}