<?php

namespace AppBundle\Service;

use AppBundle\Entity\Product;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductService
{
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function createProduct(Form $form): bool
    {
        $em = $this->registry->getManager();

        $productUsed = $em->getRepository('AppBundle:Product')
            ->findBy(array(
                'name' => $form->get('name')->getData(),
            ));

        if (is_null($productUsed)) {
            return false;
        }

        $product = new Product();

        $this->setDataInProduct($form, $product);

        $em->persist($product);
        $em->flush();

        return true;
    }

    public function editProduct(Form $form, Product $product): bool
    {
        $em = $this->registry->getManager();

        if ($em->getRepository('AppBundle:Product')->isExist(array(
            'name' => $form->get('name')->getData(),
        ), $product->getId())) {
            return false;
        }

        if ($em->getRepository('AppBundle:Product')->isExist(array(
            'sku' => $form->get('sku')->getData(),
        ), $product->getId())) {
            return false;
        }

        $this->setDataInProduct($form, $product);
        $product->setUpdateDate(new \DateTime());
        $em->flush();

        return true;
    }

    public function fillFormWithDataOfProduct(Form $form, Product $product)
    {
        $form->get('name')->setData($product->getName());
        $form->get('category')->setData($product->getCategory()->getName());
        $form->get('price')->setData($product->getPrice());
        $form->get('manufacturer')->setData($product->getManufacturer()->getName() .
            ", " . $product->getManufacturer()->getCountry());
        $form->get('description')->setData($product->getDescription());
        $form->get('sku')->setData($product->getSku());
        $form->get('isActive')->setData($product->getIsActive());
        $form->get('category_id')->setData($product->getCategory()->getId());
        $form->get('manufacturer_id')->setData($product->getManufacturer()->getId());
    }

    private function setDataInProduct(Form $form, Product $product)
    {
        $product->setDescription($form->get('description')->getData());
        $product->setName($form->get('name')->getData());
        $product->setPrice($form->get('price')->getData());
        $product->setSku($form->get('sku')->getData());
        $product->setIsActive($form->get('isActive')->getData());

        /** @var UploadedFile $file */
        $file = $form->get('image')->getData();
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        $file->move(
            'uploads/images',
            $fileName
        );

        $product->setImage($fileName);

        $categoryId = $form->get('category_id')->getData();
        $manufacturerId = $form->get('manufacturer_id')->getData();

        $category = $this->registry->getEntityManager()
            ->getReference('AppBundle:Category', intval($categoryId));
        $manufacturer = $this->registry->getEntityManager()
            ->getReference('AppBundle:Manufacturer', intval($manufacturerId));

        $product->setCategory($category);
        $product->setManufacturer($manufacturer);
    }
}