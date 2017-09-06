<?php

namespace AppBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class RegisterService
{
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function register(Form $form)
    {
        $user = $form->getData();
        $password = $user->getPassword();

        $encoder = new BCryptPasswordEncoder(12);
        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));

        $em = $this->registry->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    public function IsRegisterLogin(string $username, int $id = null): bool
    {
        $em = $this->registry->getEntityManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(array(
            'username' => $username,
        ));

        if ($user != null) {
            if ($id != null && $id == $user->getId()) {
                return false;
            }

            return true;
        }

        return false;
    }

    public function IsRegisterEmail(string $email, int $id = null): bool
    {
        $em = $this->registry->getEntityManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(array(
            'email' => $email,
        ));

        if ($user != null) {
            if ($id != null && $id == $user->getId()) {
                return false;
            }

            return true;
        }

        return false;
    }
}