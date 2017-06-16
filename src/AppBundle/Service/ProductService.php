<?php

namespace AppBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;

class ProductService
{
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function createProduct(Form $form): bool
    {
        $product = $form->getData();
        $em = $this->registry->getManager();

        $productUsed = $em->getRepository('AppBundle:Product')
            ->findBy(array(
                'name' => $product['name'],
            ));

        if (is_null($productUsed)) {
            return false;
        }

        $em->persist($product);
        $em->flush();

        return true;
    }

    public function editProduct(Form $form): bool
    {
        $product = $form->getData();
        $em = $this->registry->getManager();

        if ($em->getRepository('AppBundle:Product')->isExist(array(
            'name' => $product->getName(),
        ), $product->getId())) {
            return false;
        }

        return true;
    }
}