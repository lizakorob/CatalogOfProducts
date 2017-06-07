<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Symfony\Component\Form\Form;

class RegisterService
{
    public function register(Form $form)
    {
        $user = $form->getData();
        var_dump($user); die();
    }
}