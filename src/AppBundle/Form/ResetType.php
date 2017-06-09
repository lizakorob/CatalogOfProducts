<?php
/**
 * Created by PhpStorm.
 * User: Home-User
 * Date: 09.06.2017
 * Time: 14:19
 */

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
            ->add('new_password', PasswordType::class)
            ->add('Reset', SubmitType::class)
        ;
    }
}