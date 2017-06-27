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
                        'message' => 'error.login_used'
                    ));
            }

            if ($this->get('register_service')->IsRegisterEmail($email)) {
                return new JsonResponse(array(
                    'status' => '400',
                    'message' => 'error.email_used'
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

    /**
     * @return Response
     * @Route("/about", name="about")
     */
    public function aboutAction() {
        return $this->render('home/about.html.twig');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/change_language")
     * @Method({"POST"})
     */
    public function changeLanguageAction(Request $request) {
        $locale = $request->request->get('locale');
        $request->getSession()->set('_locale', $locale);

        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/get_language")
     * @Method({"POST"})
     */
    public function getLanguageAction(Request $request) {
        if ($locale = $request->getSession()->get('_locale')) {
            return new JsonResponse($locale);
        }

        return new JsonResponse('en');
    }
}
