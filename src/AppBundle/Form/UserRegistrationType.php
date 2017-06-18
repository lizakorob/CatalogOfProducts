<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array(
                'label' => 'Имя' ))
            ->add('surname', TextType::class, array(
                'label' => 'Фамилия' ))
            ->add('username', TextType::class, array(
                 'label' => 'Логин' ))
            ->add('email', TextType::class, array(
                'label' => 'E-mail' ))
            //->add('password', PasswordType::class, array(
             //   'label' => 'Пароль' ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Пароль'),
                'second_options' => array('label' => 'Подтвердите пароль'),
            ))
        ;
    }
}