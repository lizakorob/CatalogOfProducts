<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/users")
 */
class UserController extends Controller
{
    /**
     * @return Response
     * @Route("/", name="users")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('users/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @Route("/edit/{id}",
     *     requirements={"id" = "\d+"},
     *     defaults={"id" = 1},
     *     name="user_edit_id")
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        return $this->render('users/edit.html.twig', array());
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
}
