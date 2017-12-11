<?php

namespace AppBundle\Service;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function addCategory(Form $form): bool
    {
        $category = new Category();

        $this->setDataInCategory($form, $category);

        $this->em->persist($category);
        $this->em->flush();

        return true;
    }

    public function editCategory(Form $form, Category $category): bool
    {
        $this->setDataInCategory($form, $category);
        $this->em->flush();

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

        $categoryParent = $this->em->getRepository('AppBundle:Category')
            ->findOneBy(array(
                'name' => $form->get('parent')->getData(),
            ));

        if ($categoryParent != null) {
            $category_parent = $this->em
                ->getReference('AppBundle:Category',
					intval($categoryParent->getId()));
            $category->setParent($category_parent);
        } else {
            $category->setParent(null);
        }
    }

    /**
     * @param $name
     * @param $parent
     * @return JsonResponse
     */
    public function isExistCategoryAdd($name, $parent)
    {
        $categoryUsed = $this->em->getRepository('AppBundle:Category')
            ->findOneBy(array(
                'name' => $name,
            ));

        if ($categoryUsed != null) {
            return new JsonResponse(array(
                'status' => '400',
                'message' => 'category.exist_name'
            ));
        }

        if ($parent != '') {
            $parentCategory = $this->em->getRepository('AppBundle:Category')
                ->findOneBy(array(
                    'name' => $parent,
                ));

            if ($parentCategory == null) {
                return new JsonResponse(array(
                    'status' => '400',
                    'message' => 'category.parent_not_found'
                ));
            }
        }

        return new JsonResponse(array(
            'status' => '200',
            'message' => ''
        ));
    }

    /**
     * @param $name
     * @param Category $product
     * @param $parent
     * @return JsonResponse
     */
    public function isExistCategoryEdit($name, Category $product, $parent)
    {
        if ($this->em->getRepository('AppBundle:Category')->isExist(array(
            'name' => $name,
        ), $product->getId())) {
            return new JsonResponse(array(
                'status' => '400',
                'message' => 'category.exist_name'
            ));
        }

        if ($parent != '') {
            $parentCategory = $this->em->getRepository('AppBundle:Category')
                ->findOneBy(array(
                    'name' => $parent,
                ));

            if ($parentCategory == null) {
                return new JsonResponse(array(
                    'status' => '400',
                    'message' => 'category.parent_not_found'
                ));
            }
        }

        return new JsonResponse(array(
            'status' => '200',
            'message' => ''
        ));
    }
}