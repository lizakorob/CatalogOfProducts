<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class FilterService
{
    private $em;
    private $serialize;

    public function __construct(EntityManager $em, SerializeService $serialize)
    {
        $this->em = $em;
        $this->serialize = $serialize;
    }

    public function getByFilters(Request $request, array $ignored)
    {
        $page = $request->get('page') ? $request->get('page') : 1;
        $items = $request->get('items') ? $request->get('items') : 8;
        $sort_by_field = $request->get('sort_by_field') ? $request->get('sort_by_field') : 'id';
        $order = $request->get('order') ? $request->get('order') : 'asc';
        $filter_by_field = $request->get('filter_by_field') ? $request->get('filter_by_field') : null;
        $pattern = $request->get('pattern') ? $request->get('pattern') : null;

        $products = $this->em->getRepository('AppBundle:Product')
            ->findByPage($page, $items, $sort_by_field, $order, $filter_by_field, $pattern);

        $response = $this->serialize->serializeObjects($products, $ignored);

        return $response;
    }
}