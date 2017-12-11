<?php

namespace AppBundle\Service;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createProduct(Form $form): bool
    {
        $product = new Product();

        $this->setDataInProduct($form, $product);

		$this->em->persist($product);
		$this->em->flush();

        return true;
    }

    public function editProduct(Form $form, Product $product): bool
    {
        $this->setDataInProduct($form, $product);
        $product->setUpdateDate(new \DateTime());
		$this->em->flush();

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
        /** @var File $file */
        $file = new File('./uploads/images/' . $product->getImage());
        if ($file != null) {
            $form->get('image')->setData($file);
        }
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

        if ($file != null) {
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                'uploads/images',
                $fileName
            );

            if ($product->getImage() != null) {
                unlink('./uploads/images/' . $product->getImage());
            }
            $product->setImage($fileName);
        }

        $categoryId = $form->get('category_id')->getData();
        $manufacturerId = $form->get('manufacturer_id')->getData();

        $category = $this->em->getReference('AppBundle:Category', intval($categoryId));
        $manufacturer = $this->em->getReference('AppBundle:Manufacturer', intval($manufacturerId));

        $product->setCategory($category);
        $product->setManufacturer($manufacturer);
    }

    /**
     * @param $name
     * @param $sku
     * @return JsonResponse
     */
    public function isExistProductAdd($name, $sku)
    {
        $productUsed = $this->em->getRepository('AppBundle:Product')
            ->findOneBy(array(
                'name' => $name,
            ));

        if (!is_null($productUsed)) {
            return new JsonResponse(array(
                'status' => '400',
                'message' => 'product.exist_name'
            ));
        }

        $productUsed = $this->em->getRepository('AppBundle:Product')
            ->findOneBy(array(
                'sku' => $sku,
            ));

        if (!is_null($productUsed)) {
            return new JsonResponse(array(
                'status' => '400',
                'message' => 'product.exist_sku'
            ));
        }

        return new JsonResponse(array(
            'status' => '200',
            'message' => ''
        ));
    }

    /**
     * @param $name
     * @param $sku
     * @param Product $product
     * @return JsonResponse
     */
    public function isExistProductEdit($name, $sku, Product $product)
    {
        if ($this->em->getRepository('AppBundle:Product')->isExist(array(
            'name' => $name,
        ), $product->getId())) {
            return new JsonResponse(array(
                'status' => '400',
                'message' => 'product.exist_name'
            ));
        }

        if ($this->em->getRepository('AppBundle:Product')->isExist(array(
            'sku' => $sku,
        ), $product->getId())) {
            return new JsonResponse(array(
                'status' => '400',
                'message' => 'product.exist_sku'
            ));
        }

        return new JsonResponse(array(
            'status' => '200',
            'message' => ''
        ));
    }
}