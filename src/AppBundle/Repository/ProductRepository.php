<?php

namespace AppBundle\Repository;

class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function isExist(array $options, $id): bool
    {
        $em = $this->getEntityManager();
        $productByName = $em->getRepository('AppBundle:Product')->findOneBy($options);
        if ($productByName != null) {
            if ($productByName->getId() != $id) {
                return true;
            }
        }
        return false;
    }
    public function findByPage($page = 1,
                               $items = 8,
                               $sort_by_field = 'id',
                               $order = 'asc',
                               $filter_by_field = null,
                               $pattern = null)
    {
        if ($filter_by_field == null && $pattern == null) {
            $products = $this->_em
                ->createQueryBuilder()
                ->select('p')
                ->from('AppBundle:Product', 'p')
                ->orderBy('p.' . $sort_by_field, $order)
                ->setFirstResult(($page - 1) * $items)
                ->setMaxResults($items)
                ->getQuery()
                ->getResult();
        } else {
            $products = $this->_em
                ->createQueryBuilder()
                ->select('p')
                ->from('AppBundle:Product', 'p')
                ->innerJoin('p.category', 'cat')
                ->where('cat.name = :name')
                ->setParameter('name', $pattern)
                ->orderBy('p.' . $sort_by_field, $order)
                ->setFirstResult(($page - 1) * $items)
                ->setMaxResults($items)
                ->getQuery()
                ->getResult();
        }
        return $products;
    }
    public function getByCategory($category_id)
    {
        $products = $this->_em
            ->createQueryBuilder()
            ->select('p')
            ->from('AppBundle:Product', 'p')
            ->where('p.category.id = ' . $category_id)
            ->getQuery();
        return $products;
    }
}