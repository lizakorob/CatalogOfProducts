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
     * @Route("/", name="categories")
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();
        return $this->render('category/index.html.twig', array(
            'categories' => $categories,
        ));
    }
    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/create", name="create_category")
     */
    public function createAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(EditCategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('category')->addCategory($form);
            if (!$result) {
                $this->addFlash('error', 'Category is already created');
                return $this->redirectToRoute('create_category');
            }
            $this->addFlash('message', 'Category was created');
            return $this->redirectToRoute('categories');
        }
        return $this->render('category/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @param $id
     * @Route("/details/{id}",
     *     requirements={"id" = "\d+"},
     *     defaults={"id" = 1},
     *     name="category_by_id")
     * @return Response
     */
    public function infoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);
        if (is_null($category)) {
            throw new NotFoundHttpException('Category not found');
        }
        return $this->render('category/details.html.twig', array(
            'category' => $category,
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
            throw new NotFoundHttpException('Category not found');
        }
        $form = $this->createForm(EditCategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('category')->editCategory($form);
            if ($result) {
                $this->addFlash('error', 'Category is already created');
                return $this->redirectToRoute('category_edit_id', array(
                    'id' => $id,
                ));
            }
            $this->addFlash('message', 'Category was changed');
            return $this->redirectToRoute('categories');
        }
        return $this->render('category/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @param $id
     * @return Response
     * @Route("/delete/{id}",
     *     requirements={"id" = "\d+"},
     *     defaults={"id" = 1},
     *     name="categories_delete_id")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);
        if (is_null($category)) {
            throw new NotFoundHttpException('Category is not found');
        }
        return $this->render('category/delete.html.twig', array(
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
        $em->remove($category);
        $em->flush();
        $this->addFlash('message', 'Category was deleted');
        return $this->redirectToRoute('categories');
    }
}