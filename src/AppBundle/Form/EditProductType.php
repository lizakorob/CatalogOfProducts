<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EditProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', ButtonType::class)
            ->add('name', TextType::class)
            ->add('category', TextType::class)
            ->add('manufacturer', TextType::class)
            ->add('price', NumberType::class)
            ->add('description', TextareaType::class)
            ->add('isActive', CheckboxType::class)
            ->add('Save', SubmitType::class)
        ;
    }
}