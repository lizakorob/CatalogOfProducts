<?php

namespace AppBundle\Service;

use AppBundle\Entity\Category;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryService
{
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function addCategory(Form $form): bool
    {
        $em = $this->registry->getManager();

        $category = new Category();

        $this->setDataInCategory($form, $category);

        $em->persist($category);
        $em->flush();

        return true;
    }

    public function editCategory(Form $form, Category $category): bool
    {
        $em = $this->registry->getManager();

        $this->setDataInCategory($form, $category);
        $em->flush();

        return true;
    }

    public function fillFormWithDataOfCategory(Form $form, Category $category)
    {
        $form->get('name')->setData($category->getName());
        if ($category->getParent() != null) {
            $form->get('parent')->setData($category->getParent()->getName());
            $form->get('category_id')->setData($category->getParent()->getId());
        }
    }

    private function setDataInCategory(Form $form, Category $category)
    {
        $category->setName($form->get('name')->getData());

        $categoryId = $form->get('category_id')->getData();
        $categoryId = $this->registry->getManager()->getRepository('AppBundle:Category')
            ->findBy(array(
                'name' => $form->get('parent')->getData(),
            ));

        if ($form->get('parent')->getData() != '') {
            $category_parent = $this->registry->getEntityManager()
                ->getReference('AppBundle:Category', intval($categoryId));
            $category->setParent($category_parent);
        } else {
            $category->setParent(null);
        }
    }

    /**
     * @param $name
     * @return JsonResponse
     */
    public function isExistCategoryAdd($name)
    {
        $em = $this->registry->getManager();

        $categoryUsed = $em->getRepository('AppBundle:Category')
            ->findOneBy(array(
                'name' => $name,
            ));

        if ($categoryUsed != null) {
            return new JsonResponse(array(
                'status' => '400',
                'message' => 'Категория с таким именем уже существует'
            ));
        } else {

            return new JsonResponse(array(
                'status' => '200',
                'message' => ''
            ));
        }
    }

    /**
     * @param $name
     * @param Category $product
     * @return JsonResponse
     */
    public function isExistCategoryEdit($name, Category $product)
    {
        $em = $this->registry->getManager();

        if ($em->getRepository('AppBundle:Category')->isExist(array(
            'name' => $name,
        ), $product->getId())) {
            return new JsonResponse(array(
                'status' => '400',
                'message' => 'Категория с таким именем уже существует'
            ));
        }

        return new JsonResponse(array(
            'status' => '200',
            'message' => ''
        ));
    }
}