<?php

namespace AppBundle\Controller;

use AppBundle\Form\ForgotType;
use AppBundle\Form\ResetType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        $authUtils->getLastAuthenticationError();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/sign", name="sign")
     * @Method({"POST"})
     */
    public function signAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $username = $request->request->get('username');
            $password = $request->request->get('password');

            $flag = $this->get('login_service')->IsUser($username, $password);

            if (!$flag) {
                return new JsonResponse(array(
                    'status' => '400',
                    'message' => 'Некорректный логин или пароль'
                ));
            }

            return new JsonResponse(array(
                'status' => '200',
                'message' => ''
            ));
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse | Response
     * @Route("/forgot_password", name="forgot_password")
     * @Method({"POST"})
     */
    public function forgotPasswordAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authorization_checker');
        if ($authenticationUtils->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }

        if ($request->isXmlHttpRequest()) {
            $email = $request->request->get('email');

            if(!$this->get('forgot_password')->IsRegisterEmail($email)) {
                return new JsonResponse(array(
                    'status' => '400',
                    'message' => 'Пользователь с таким e-mail не найден'
                ));
            }

            return new JsonResponse(array(
                'status' => '200',
                'message' => ''
            ));
        }

        $form = $this->createForm(ForgotType::class);
        $form->handleRequest($request);

        $this->get('forgot_password')->sendResetPasswordEmail($form);

        $this->addFlash('message', 'Письмо с инструкцией по восстановлению пароля отправлено на ваш e-mail');
        return $this->redirectToRoute('homepage');
    }

    /**
     * @param Request $request
     * @param $hash
     * @return Response|RedirectResponse
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
            $this->addFlash('message', 'Ссылка недействительна');
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(ResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $result = $this->get('reset_password')->resetPassword($form, $hash);

            if (!$result) {
                throw new NotFoundHttpException();
            }

            $this->addFlash('message', 'Пароль был успешно изменен');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('security/forgot_password.html.twig', array(
            'form' => $form->createView(),
            'hash' => $hash,
        ));
    }
}
