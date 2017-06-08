<?php

namespace AppBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class ResetService
{
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function resetPassword(Form $form, string $hash): bool
    {
        $em = $this->registry->getManager();
        $userReset = $em->getRepository('AppBundle:ForgotPassword')
            ->findOneBy(array(
                'hashCode' => $hash,
            ));

        if (is_null($userReset)) {
            return false;
        }

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(array(
                'email' => $userReset->getEmail(),
            ));

        $encoder = new BCryptPasswordEncoder(12);
        $newPassword = $form->get('new_password')->getData();
        $user->setPassword($encoder->encodePassword($newPassword, $user->getSalt()));
        $em->flush();

        $this->deleteHash($hash);
        return true;
    }

    public function deleteHash(string $hash)
    {
        $em = $this->registry->getManager();
        $userReset = $em->getRepository('AppBundle:ForgotPassword')
            ->findOneBy(array(
                'hashCode' => $hash,
            ));
        $em->remove($userReset);
        $em->flush();
    }
}