<?php

namespace AppBundle\Controller;

use AppBundle\Form\ForgotType;
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

        $form = $this->createForm(ForgotType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

        }

        return $this->render('security/forgot_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/reset_password/{hash}", name="reset_password")
     */
    public function resetPasswordAction(Request $request)
    {
    }
}
