<?php

namespace AppBundle\Controller;

use AppBundle\Form\EditCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CategoryController
 * @package AppBundle\Controller
 *
 * @Security("has_role('ROLE_MODERATOR')")
 */
class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @Route("/categories", name="categories")
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render('category/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * @param $id
     * @Route("/categories/details/{id}",
     *     requirements={"id" = "/d+"},
     *     name="category_by_id")
     * @return Response
     */
    public function infoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);

        if (is_null($category)) {
            throw new NotFoundHttpException();
        }

        return $this->render('category/details.html.twig', array(
            'category' => $category,
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     *
     * @Route("/categories/edit/{id}",
     *     requirements={"id" = "/d+"},
     *     name="category_edit_id")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);

        if (is_null($category)) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(EditCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('category')->editCategory($form);

            if ($result) {
                $this->addFlash('message', 'Category is already created');
                return $this->redirectToRoute('category_edit_id', array(
                    'id' => $id,
                ));
            }

            $this->addFlash('message', 'Category has been changed');
            return $this->redirectToRoute('categories');
        }

        return $this->render('category/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function deleteAction()
    {

    }
}
