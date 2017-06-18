<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class UserLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, array(
                'label' => 'E-mail или логин',))
            ->add('password', PasswordType::class, array(
                'label' => 'Пароль'))
        ;
    }
}