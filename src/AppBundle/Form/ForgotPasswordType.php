<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ForgotPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, array(
                'label' => 'Электронная почта' ))
            ->add('Send', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-default'),
                    'label' => 'Отправить') )
        ;
    }
}