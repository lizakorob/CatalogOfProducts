<?php

namespace AppBundle\Controller;

use AppBundle\Form\EditUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/users")
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/", name="users")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $response = $this->get('filter_service')->getByFilters($request, 'AppBundle:User');

            return $response;
        }

        return $this->render('users/index.html.twig');
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response|JsonResponse
     * @Route("/edit/{id}",
     *     requirements={"id" = "\d+"},
     *     name="user_edit_id")
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        if (is_null($user)) {
            throw new NotFoundHttpException('Продукт не найден');
        }

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $email = $request->request->get('email');
            $username = $request->request->get('username');

            if ($this->get('register_service')->IsRegisterLogin($username, $this->getUser()->getId())) {
                return new JsonResponse(array(
                    'status' => '400',
                    'message' => 'Логин уже используется'
                ));
            }

            if ($this->get('register_service')->IsRegisterEmail($email, $this->getUser()->getId())) {
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

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em->flush();

            $this->addFlash('message', 'Данные вышей учетной записи были изменены');
            return $this->redirect('/users/details/' . $user->getId());
        }

        if ($this->getUser()->getId() != $user->getId()) {
            return $this->redirect('/users/details/' . $user->getId());
        }

        return $this->render('users/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $id
     * @return Response
     * @Route("/details/{id}",
     *     requirements={"id" = "\d+"},
     *     defaults={"id" = 1},
     *     name="user_details")
     * @Security("has_role('ROLE_USER')")
     */
    public function detailsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        return $this->render('users/details.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @Route("/delete/{id}",
     *     requirements={"id" = "\d+"},
     *     defaults={"id" = 1})
     * @Method("POST")
     */
    public function deleteConfirmAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        if (is_null($user)) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        $em->remove($user);
        $em->flush();

        $this->addFlash('message', 'Пользователь был успешно удален');
        return $this->redirectToRoute('users');
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/get_count")
     */
    public function getCountRows(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $length = $this->get('filter_service')->getCountRows($request, 'AppBundle:User');

            return $length;
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/set_role")
     */
    public function setRoleForUser(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $role = $request->get('role');
            $userId = $request->get('userId');

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')->find(intval($userId));

            $user->setRole($role);
            $em->flush();

            return new JsonResponse('Роль пользователя была изменена');
        }
    }
}
