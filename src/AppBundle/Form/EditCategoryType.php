<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EditCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'category.name',
                'label_attr' => array('class' => 'col-xs-12 col-md-4'),
                'attr'       => array('class' => 'col-xs-12 col-md-6'),))
            ->add('parent', TextType::class, array(
                'label' => 'category.parent',
                'label_attr' => array('class' => 'col-xs-12 col-md-4'),
                'attr' => array('class' => 'categoryField col-xs-12 col-md-6',
                    'required' => 'false'), 'required' => 'false'))
            ->add('category_id', HiddenType::class, array(
                'attr' => array(
                    'class' => 'category_idField',
                ),
            ))
        ;
    }
}