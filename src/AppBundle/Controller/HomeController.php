<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Form\EditProductType;
use AppBundle\Form\ForgotPasswordType;
use AppBundle\Form\UserLoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\UserRegistrationType;
use Symfony\Component\Validator\Constraints\Date;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
//    public function indexAction()
//    {
//        // replace this example code with whatever you need
//        $user = new User();
//        $loginForm = $this->createForm(UserLoginType::class, $user);
//        $registerForm = $this->createForm(UserRegistrationType::class, $user);
//        $forgotPasswordForm = $this->createForm(ForgotPasswordType::class);
//        //заглушка для редактирования товара
//        $product = new Product();
//        $product->setName('товар1');
//        $product->setDescription('красивый');
//        $product->setPrice(100);
//        $product->setIsActive(true);
////        $product->setCategory(1);
//        $form = $this->createForm(EditProductType::class, $product);
//
//        return $this->render('products/edit.html.twig', array(
////            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
//            'loginForm' => $loginForm->createView(),
//            'registerForm' => $registerForm->createView(),
//            'forgotPasswordForm' => $forgotPasswordForm->createView(),
//            'form' => $form->createView(),
//        ));
//    }

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
        $loginForm = $this->createForm(UserLoginType::class);
        $formRegister = $this->createForm(UserRegistrationType::class);
        $formForgot = $this->createForm(ForgotPasswordType::class);
        return $this->render('home/index.html.twig', array(
            'loginForm' => $loginForm->createView(),
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
        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('register_service')->register($form);
            return $this->redirectToRoute('homepage');
        }
    }
}
