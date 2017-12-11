<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class RegisterService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function register(Form $form)
    {
        $user = $form->getData();
        $password = $user->getPassword();

        $encoder = new BCryptPasswordEncoder(12);
        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));

        $this->em->persist($user);
        $this->em->flush();
    }

    public function IsRegisterLogin(string $username, int $id = null): bool
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array(
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
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array(
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