<?php

namespace AppBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class RegisterService
{
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function register(Form $form, Request $request)
    {
        $user = $form->getData();
        $password = $user->getPassword();
        $confirmPassword = $request->request->get('_confirmPassword');

        if ($confirmPassword != $password) {
            return 'Confirm password do not match.';
        }

        if ($this->IsRegisterEmail($user->getEmail())) {
            return 'Email is already used.';
        }

        if ($this->IsRegisterLogin($user->getUsername())) {
            return 'Username is already used.';
        }

        $encoder = new BCryptPasswordEncoder(12);
        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));

        $em = $this->registry->getEntityManager();
        $em->persist($user);
        $em->flush();

        return null;
    }

    private function IsRegisterLogin(string $username): bool
    {
        $em = $this->registry->getEntityManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(array(
            'username' => $username,
        ));

        if ($user != null) {
            return true;
        }

        return false;
    }

    private function IsRegisterEmail(string $email): bool
    {
        $em = $this->registry->getEntityManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(array(
            'email' => $email,
        ));

        if ($user != null) {
            return true;
        }

        return false;
    }
}