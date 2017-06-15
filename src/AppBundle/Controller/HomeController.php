<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ForgotType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\RegisterType;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $authenticationUtils = $this->get('security.authorization_checker');
        if ($authenticationUtils->isGranted('ROLE_USER')) {
            return $this->render('home/index.html.twig');
        }

        $formRegister = $this->createForm(RegisterType::class);
        $formForgot = $this->createForm(ForgotType::class);
        return $this->render('home/index.html.twig', array(
            'registerForm' => $formRegister->createView(),
            'forgotPasswordForm' => $formForgot->createView(),
        ));
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/register", name="register")
     * @Method({"POST"})
     */
    public function registerAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $email = $request->request->get('email');
            $username = $request->request->get('username');

            if ($this->get('register_service')->IsRegisterLogin($username)) {
                return new JsonResponse(array(
                        'status' => '400',
                        'message' => 'Логин уже используется'
                    ));
            }

            if ($this->get('register_service')->IsRegisterEmail($email)) {
                return new JsonResponse(array(
                    'status' => '400',
                    'message' => 'E-mail уже используется'
                ));
            }

            return new JsonResponse(array(
                'status' => '200',
                'message' => ''
            ));
        }

        $user = new User();
        $error = null;
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('register_service')->register($form);

            return $this->redirectToRoute('homepage');
        }
    }
}
