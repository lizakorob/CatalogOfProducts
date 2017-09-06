<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EditProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'product.name',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr'       => array('class' => 'col-xs-12 col-md-9'),))
            ->add('category', TextType::class, array(
                'label' => 'product.category',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr'       => array('class' => 'categoryField col-xs-12 col-md-9')))
            ->add('price', IntegerType::class, array(
                'label' => 'product.cost',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr'       => array('class' => 'col-xs-12 col-md-9',
                    'min' => '0.01', 'max' => '100', 'step' => '0.01')))
            ->add('manufacturer', TextType::class, array(
                'label' => 'product.manufacturer',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr'       => array('class' => 'manufacturerField col-xs-12 col-md-9'),))
            ->add('description', TextareaType::class, array(
                'label' => 'product.description',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr'       => array('class' => 'col-xs-12 col-md-9')))
            ->add('sku', IntegerType::class, array(
                'label' => 'SKU',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr'       => array('class' => 'col-xs-12 col-md-9',
                    'min' => '100000000000', 'max' => '999999999999')))
            ->add('isActive', CheckboxType::class, array(
                'label' => 'product.is_active',
                'label_attr' => array('class' => 'col-xs-5 col-md-3'),
                'attr'       => array('class' => 'col-xs-1 col-xs-offset-6 col-md-1 col-md-offset-8')))
            ->add('image', FileType::class, array(
                'label' => 'product.image',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr' => array('accept' => 'image/jpeg,image/png',
                    'onchange' => 'loadFile(event)',
                    'class' => 'col-xs-12 col-md-7'),
                'required' => false,))
            ->add('category_id', HiddenType::class, array(
                'attr' => array(
                    'class' => 'category_idField',
                ),
            ))
            ->add('manufacturer_id', HiddenType::class)
        ;
    }
}