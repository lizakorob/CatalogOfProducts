<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\EditCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CategoryController
 * @package AppBundle\Controller
 * @Route("/categories")
 * @Security("has_role('ROLE_MODERATOR')")
 */
class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @Route("/", name="categories")
     * @return Response
     */
    public function indexAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $response = $this->get('filter_service')->getByFilters($request, 'AppBundle:Category');

            return $response;
        }

        return $this->render('category/index.html.twig');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/create", name="create_category")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(EditCategoryType::class);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $name = $request->request->get('name');
            $parent = $request->request->get('parent');

            return $this->get('category')->isExistCategoryAdd($name, $parent);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('category')->addCategory($form);

            $this->addFlash('message', 'category.add_success');
            return $this->redirectToRoute('categories');
        }

        return $this->render('category/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     *
     * @Route("/edit/{id}",
     *     requirements={"id" = "\d+"},
     *     defaults={"id" = 1},
     *     name="category_edit_id")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);

        if (is_null($category)) {
            throw new NotFoundHttpException('categoty.not_found');
        }

        $form = $this->createForm(EditCategoryType::class);
        $this->get('category')->fillFormWithDataOfCategory($form, $category);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $name = $request->request->get('name');
            $parent = $request->request->get('parent');

            return $this->get('category')->isExistCategoryEdit($name, $category, $parent);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('category')->editCategory($form, $category);

            $this->addFlash('message', 'category.edit_success');
            return $this->redirectToRoute('categories');
        }

        return $this->render('category/edit.html.twig', array(
            'form' => $form->createView(),
            'category' => $category,
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
        $category = $em->getRepository('AppBundle:Category')->find($id);

        if (is_null($category)) {
            throw new NotFoundHttpException('category.not_found');
        }

        $em->remove($category);
        $em->flush();

        $this->addFlash('message', 'category.delete');
        return $this->redirectToRoute('categories');
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/get_count")
     */
    public function getCountRows(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $length = $this->get('filter_service')->getCountRows($request, 'AppBundle:Category');

            return $length;
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/get_by_filter")
     */
    public function getByFilter(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $filter = $request->get('filter');
            $categoryName = $request->get('category');

            $em = $this->getDoctrine()->getManager();
            $categories = $em->getRepository('AppBundle:Category')
                ->findByFilter($filter, $categoryName);

            $response = $this->get('serialize_service')->serializeObjects($categories);

            return $response;
        }
    }
}
