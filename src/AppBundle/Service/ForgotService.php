<?php

namespace AppBundle\Service;

use AppBundle\Entity\ForgotPassword;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;

class ForgotService
{
    private $em;
    private $mailer;

    public function __construct(EntityManager $em, \Swift_Mailer $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function sendResetPasswordEmail(Form $form)
    {
        $email = $form->get('email')->getData();

        $userReset = $this->em->getRepository('AppBundle:ForgotPassword')
            ->findOneBy(array(
                'email' => $email,
            ));
        if (!is_null($userReset)) {
			$this->em->remove($userReset);
			$this->em->flush();
        }

        $hash = md5(uniqid(null, true));
        $this->sendMessage($email, $hash);
    }

    private function sendMessage(string $email, string $hash)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('allexuss@list.ru')
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
        $forgotPassword->setCreateDate(new \DateTime());

		$this->em->persist($forgotPassword);
		$this->em->flush();
    }

    public function IsRegisterEmail(string $email): bool
    {
        $user = $this->em->getRepository('AppBundle:User')
            ->findOneBy(array(
                'email' => $email,
            ));

        if (is_null($user)) {
            return false;
        }

        return true;
    }
}