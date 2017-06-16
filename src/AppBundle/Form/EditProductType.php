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
                'label' => 'Название',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr'       => array('class' => 'col-xs-12 col-md-9'),))
            ->add('category', TextType::class, array(
                'label' => 'Категория',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr'       => array('class' => 'col-xs-12 col-md-9')))
            ->add('price', IntegerType::class, array(
                'label' => 'Стоимость',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr'       => array('class' => 'col-xs-12 col-md-9',
                    'min' => '0', 'max' => '100', 'step' => '0.01')))
            ->add('manufacturer', TextType::class, array(
                'label' => 'Производитель',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr'       => array('class' => 'col-xs-12 col-md-9'),))
            ->add('description', TextareaType::class, array(
                'label' => 'Описание',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr'       => array('class' => 'col-xs-12 col-md-9')))
            ->add('sku', IntegerType::class, array(
                'label' => 'SKU',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr'       => array('class' => 'col-xs-12 col-md-9',
                    'min' => '0', 'max' => '999999999999', 'step' => '')))
            ->add('isActive', CheckboxType::class, array(
                'label' => 'В наличии',
                'label_attr' => array('class' => 'col-xs-5 col-md-3'),
                'attr'       => array('class' => 'col-xs-1 col-xs-offset-6 col-md-1 col-md-offset-8')))
            ->add('image', FileType::class, array(
                'label' => 'Изображение',
                'label_attr' => array('class' => 'col-xs-12 col-md-3'),
                'attr' => array('accept' => 'image/jpeg,image/png',
                    'onchange' => 'loadFile(event)',
                    'class' => 'col-xs-12 col-md-7'),
                'required' => false,))
            ->add('category_id', HiddenType::class)
            ->add('manufacturer_id', HiddenType::class)
        ;
    }
}