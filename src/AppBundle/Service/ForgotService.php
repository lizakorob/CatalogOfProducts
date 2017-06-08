<?php

namespace AppBundle\Service;

use AppBundle\Entity\ForgotPassword;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;

class ForgotService
{
    private $registry;
    private $mailer;

    public function __construct(RegistryInterface $registry, \Swift_Mailer $mailer)
    {
        $this->registry = $registry;
        $this->mailer = $mailer;
    }

    public function sendResetPasswordEmail(Form $form)
    {
        $email = $form->get('email')->getData();
        $em = $this->registry->getEntityManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(array(
                'email' => $email,
            ));

        if (is_null($user)) {
            return false;
        }

        $hash = md5(uniqid(null, true));
        $this->sendMessage($email, $hash);

        return true;
    }

    private function sendMessage(string $email, string $hash)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Reset password')
            ->setFrom('sweets@mail.ru')
            ->setTo($email)
            ->setBody('To reset you password please
                    follow this link http://localhost:8000/reset_password/' . $hash);

        $this->mailer->send($message);
        $this->addEmailAndHash($email, $hash);
    }

    private function addEmailAndHash(string $email, string $hash)
    {
        $forgotPassword = new ForgotPassword();
        $forgotPassword->setEmail($email);
        $forgotPassword->setHashCode($hash);

        $em = $this->registry->getEntityManager();
        $em->persist($forgotPassword);
        $em->flush();
    }
}