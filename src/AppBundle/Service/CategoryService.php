<?php

namespace AppBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;

class CategoryService
{
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function addCategory(Form $form): bool
    {
        $category = $form->getData();
        $em = $this->registry->getManager();

        $categoryUsed = $em->getRepository('AppBundle:Category')
            ->findBy(array(
                'name' => $category->getName(),
            ));

        if (is_null($categoryUsed)) {
            return false;
        }

        $em->persist($category);
        $em->flush();

        return true;
    }

    public function editCategory(Form $form): bool
    {
        $category = $form->getData();
        $em = $this->registry->getManager();

        if ($em->getRepository('AppBundle:Category')->isExist(array(
            'name' => $category->getName(),
        ), $category->getId())) {
            return false;
        }

        return true;
    }
}