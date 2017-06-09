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
        return true;
    }

    public function editProduct(Form $form): bool
    {
        return true;
    }
}