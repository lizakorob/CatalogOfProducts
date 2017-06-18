<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ResetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('new_password', PasswordType::class, array(
                'label' => 'Новый пароль',
                'label_attr' => array('class' => 'col-xs-12 col-md-4'),
                'attr'       => array('class' => 'col-xs-12 col-md-6'),))
        ;
    }
}