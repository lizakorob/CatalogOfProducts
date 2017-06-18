<?php

namespace AppBundle\Service;


use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class LoginService
{
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function IsUser(string $username, string $password): bool
    {
        $em = $this->registry->getEntityManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(array(
            'username' => $username,
        ));

        if (is_null($user)) {
            return false;
        }

        $encoder = new BCryptPasswordEncoder(12);
        return $encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());
    }
}