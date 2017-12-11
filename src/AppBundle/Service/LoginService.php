<?php

namespace AppBundle\Service;


use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class LoginService
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function IsUser(string $username, string $password): bool
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array(
            'username' => $username,
        ));

        if (is_null($user)) {
            return false;
        }

        $encoder = new BCryptPasswordEncoder(12);
        return $encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());
    }
}