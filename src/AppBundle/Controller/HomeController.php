<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ForgotPasswordType;
use AppBundle\Form\UserLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\UserRegistrationType;
use Symfony\Component\Validator\Constraints\Date;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // replace this example code with whatever you need
        $user = new User();
        $loginForm = $this->createForm(UserLoginType::class, $user);
        $registerForm = $this->createForm(UserRegistrationType::class, $user);
        $forgotPasswordForm = $this->createForm(ForgotPasswordType::class);
        return $this->render('default/index.html.twig', array(
//            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'loginForm' => $loginForm->createView(), 'registerForm' => $registerForm->createView(),
            'forgotPasswordForm' => $forgotPasswordForm->createView(),
        ));
    }
    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authorization_checker');
        if ($authenticationUtils->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }
        $user = new User();
        $error = null;
        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $error = $this->get('register_service')->register($form, $request);
            if ($error != null) {
                return $this->render('home/register.html.twig', array(
                    'form' => $form->createView(),
                    'error' => $error,
                ));
            }
            return $this->redirectToRoute('homepage');
        }
        return $this->render('home/register.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
        ));
    }
}
