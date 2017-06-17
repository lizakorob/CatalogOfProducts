<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\EditProductType;
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
 * Class ProductController
 * @package AppBundle\Controller
 * @Route("/products")
 */
class ProductController extends Controller
{
    /**
     * @return Response
     * @Route("/", name="products")
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $request->getSession()->set('page', 1);
            $request->getSession()->set('items', 8);
            $request->getSession()->set('order', null);
            $request->getSession()->set('sortbyfield', null);
            $request->getSession()->set('filterbyfield', null);
            $request->getSession()->set('pattern', null);

            $products = $em->getRepository('AppBundle:Product')
                ->findByPage();

            $response = $this->get('serialize_service')->serializeObjects($products, [
                'createDate',
                'updateDate',
                'sku'
            ]);

            return $response;
        }

        return $this->render('products/index.html.twig');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response|JsonResponse
     * @Route("/create", name="product_create")
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(EditProductType::class);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $name = $request->request->get('name');
            $sku = $request->request->get('sku');

            return $this->get('products')->isExistProductAdd($name, $sku);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('products')->createProduct($form);

            $this->addFlash('message', 'Продукт был успешно создан.');
            return $this->redirectToRoute('products');
        }

        return $this->render('products/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $id
     * @return Response
     * @Route("/details/{id}",
     *     requirements={"id" = "\d+"},
     *     defaults={"id" = 1},
     *     name="product_details")
     * @Security("has_role('ROLE_USER')")
     */
    public function detailsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($id);

        if (is_null($product)) {
            throw new NotFoundHttpException('Продукт не найден');
        }

        return $this->render('products/details.html.twig', array(
            'product' => $product,
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     * @Route("/edit/{id}",
     *     requirements={"id" = "\d+"},
     *     defaults={"id" = 1},
     *     name="product_edit_id")
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($id);

        if (is_null($product)) {
            throw new NotFoundHttpException('Продукт не найден');
        }

        $form = $this->createForm(EditProductType::class);
        $this->get('products')->fillFormWithDataOfProduct($form, $product);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $name = $request->request->get('name');
            $sku = $request->request->get('sku');

            return $this->get('products')->isExistProductEdit($name, $sku, $product);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('products')->editProduct($form, $product);

            $this->addFlash('message', 'Продукт был успешно изменен');
            return $this->redirectToRoute('products');
        }

        return $this->render('products/edit.html.twig', array(
            'form' => $form->createView(),
            'product' => $product,
        ));
    }

    /**
     * @param $id
     * @return Response
     * @Route("/delete/{id}",
     *     requirements={"id" = "\d+"},
     *     defaults={"id" = 1},
     *     name="product_delete")
     * @Method({"GET"})
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($id);

        if (is_null($product)) {
            throw new NotFoundHttpException('Product not found');
        }

        return $this->render('products/delete.html.twig', array(
            'product' => $product,
        ));
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @Route("/delete/{id}",
     *     requirements={"id" = "\d+"},
     *     defaults={"id" = 1})
     * @Method({"POST"})
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function deleteConfirmAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->find($id);

        $em->remove($product);
        $em->flush();

        $this->addFlash('message', 'Product was deleted');
        return $this->redirectToRoute('products');
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/get_all_categories", name="get_all_categories")
     * @Method({"POST"})
     */
    public function getAllCategoriesAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $categories = $em->getRepository('AppBundle:Category')->findAll();

            $response = $this->get('serialize_service')->serializeObjects($categories, ['parent']);

            return $response;
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/get_all_manufacturers", name="get_all_manufacturers")
     * @Method({"POST"})
     */
    public function getAllManufacturersAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $manufacturers = $em->getRepository('AppBundle:Manufacturer')->findAll();

            $response = $this->get('serialize_service')->serializeObjects($manufacturers);

            return $response;
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/?page={page}",
     *     requirements={"page" = "\d+"},
     *     defaults={"page" = 1})
     * @Security("has_role('ROLE_USER')")
     */
    public function getByPageAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            $request->getSession()->set('page', 2);
            $request->getSession()->set('items', 8);
            $request->getSession()->set('order', null);
            $request->getSession()->set('sortbyfield', null);
            $request->getSession()->set('filterbyfield', null);
            $request->getSession()->set('pattern', null);

            $products = $em->getRepository('AppBundle:Product')
                ->findByPage(2);

            $response = $this->get('serialize_service')->serializeObjects($products, [
                'createDate',
                'updateDate',
                'sku'
            ]);

            return $response;
        }
    }
}
