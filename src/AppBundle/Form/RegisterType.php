<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array(
                'label' => 'modal.name' ))
            ->add('surname', TextType::class, array(
                'label' => 'modal.surname' ))
            ->add('username', TextType::class, array(
                'label' => 'modal.username' ))
            ->add('email', EmailType::class, array(
                'label' => 'modal.email' ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'modal.password'),
                'second_options' => array('label' => 'modal.confirm_password'),
            ))
        ;
    }
}