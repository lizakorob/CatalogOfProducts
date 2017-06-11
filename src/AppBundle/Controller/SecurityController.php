<?php

namespace AppBundle\Controller;

use AppBundle\Form\ForgotPasswordType;
use AppBundle\Form\ResetType;
use AppBundle\Form\UserLoginType;
use AppBundle\Form\UserRegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        $authenticationUtils = $this->get('security.authorization_checker');
        if ($authenticationUtils->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();
        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
    /**
     * @Route("/forgot_password", name="forgot_password")
     */
    public function forgotPasswordAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authorization_checker');
        if ($authenticationUtils->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $result = $this->get('forgot_password')->sendResetPasswordEmail($form);
            if (!$result) {
                $this->addFlash('message', 'User with this email not found!');
                return $this->redirectToRoute('forgot_password');
            }
            $this->addFlash('message', 'Message with instructions was send to your email!');
            return $this->redirectToRoute('login');
        }
        return $this->render('security/forgot_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/reset_password/{hash}", name="reset_password")
     */
    public function resetPasswordAction(Request $request, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $userReset = $em->getRepository('AppBundle:ForgotPassword')
            ->findOneBy(array(
                'hashCode' => $hash,
            ));
        if (date_diff(new \DateTime(), $userReset->getCreateDate())->h > 2) {
            $this->get('reset_password')->deleteHash($hash);
            $this->addFlash('message', 'Link is not valid');
            return $this->redirectToRoute('forgot_password');
        }
        $form = $this->createForm(ResetType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $result = $this->get('reset_password')->resetPassword($form, $hash);
            if (!$result) {
                $this->addFlash('message', 'Link is not correct');
                return $this->redirectToRoute('forgot_password');
            }
            $this->addFlash('message', 'Password has been changed');
            return $this->redirectToRoute('login');
        }
        return $this->render('security/forgot_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
