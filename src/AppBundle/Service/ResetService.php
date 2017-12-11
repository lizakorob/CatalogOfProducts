<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class ResetService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function resetPassword(Form $form, string $hash): bool
    {
        $userReset = $this->em->getRepository('AppBundle:ForgotPassword')
            ->findOneBy(array(
                'hashCode' => $hash,
            ));

        if (is_null($userReset)) {
            return false;
        }

        $user = $this->em->getRepository('AppBundle:User')
            ->findOneBy(array(
                'email' => $userReset->getEmail(),
            ));

        $encoder = new BCryptPasswordEncoder(12);
        $newPassword = $form->get('new_password')->getData();
        $user->setPassword($encoder->encodePassword($newPassword, $user->getSalt()));
		$this->em->flush();

        $this->deleteHash($hash);
        return true;
    }

    public function deleteHash(string $hash)
    {
        $userReset = $this->em->getRepository('AppBundle:ForgotPassword')
            ->findOneBy(array(
                'hashCode' => $hash,
            ));
		$this->em->remove($userReset);
		$this->em->flush();
    }
}