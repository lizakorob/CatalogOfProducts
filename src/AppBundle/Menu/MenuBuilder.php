<?php

namespace AppBundle\Menu;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MenuBuilder
{
    private $factory;
    private $container;
    private $em;

    public function __construct(FactoryInterface $factory, ContainerInterface $container, EntityManager $em)
    {
        $this->factory = $factory;
        $this->container = $container;
        $this->em = $em;
    }

    public function setChildrenItem($item, array $categories, Category $currentCategory)
    {
        foreach ($categories as $category) {
            if ($category->getParent() != null) {
                if ($category->getParent()->getId() === $currentCategory->getId()) {
                    $item->addChild($category->getName(), array(
                        'route' => 'products',
                        'routeParameters' => array(
                            'category' => $category->getName())
                    ))
                        ->setLinkAttribute('onclick', 'return false;')
                        ->getParent();
                    //$this->setChildrenItem($item, $array, $category);
                }
            }
        }
    }

    public function createMainMenu(array $options)
    {
        $category1 = new Category(1, 'КОНФЕТЫ', null, 'sweets');
        $category2 = new Category(2, 'ПЕЧЕНЬЕ', null, 'cookies');
        $category3 = new Category(3, 'ПОДАРКИ', null, 'presents');
        $category22 = new Category(31, 'ЕВРОП. СЛАД.', null, 'presents');
        $category223 = new Category(32, 'ЕЩЕ2', null, 'presents');
        $category32 = new Category(333, 'ЕЩЕ3', null, 'presents');
        $category4 = new Category(4, 'Леденцы', 1, '123');
        $category5 = new Category(5, 'Шоколадные', 1, '123');
        $category6 = new Category(6, 'Суфле', 1, '123');
        $category7 = new Category(7, 'Бисквитное', 2, '123');
        $category8 = new Category(8, 'Вафли', 2, '123');
        $category9 = new Category(9, 'Для нее', 3, '23');
        $category10 = new Category(10, 'Для него', 3, '123');
        $category11 = new Category(11, 'На новый год', 3, '123');
        $cat = new Category(100, 'fbfdfbfd', 4, '111');
        $array = [$category1, $category2, $cat, $category3, $category4, $category5, $category6,
            $category7, $category8, $category8, $category9, $category10, $category11, $category22, $category32, $category223];
        $menu = $this->factory->createItem('main');
        $menu->setChildrenAttribute('class', 'nav navbar-nav mainMenu col-md-12');
        foreach ($array as $category) {
            if ($category->getParent() == null) {
                $item = $menu->addChild($category->getName(), array('route' => 'login'))
                    ->setAttribute('class', 'dropdown col-xs-6 col-sm-2')
                    ->setAttribute('icon', 'fa fa-home')
                    ->setLinkAttribute('data-toggle', 'dropdown')
                    ->setLinkAttribute('class', 'dropdown-toggle disabled')
                    ->setChildrenAttribute('class', 'dropdown-menu')
                    ->setChildrenAttribute('role', 'menu');

                $this->setChildrenItem($item, $array, $category);
            }
        }
        return $menu;
    }

    public function createSidebarMenu(array $options)
    {
        $categories = $this->em->getRepository('AppBundle:Category')->findAll();

        $menu = $this->factory->createItem('accordeon');

        $menu->setChildrenAttribute('class', 'sidebar');
        foreach ($categories as $category) {
            if ($category->getParent() == null) {
                $item = $menu->addChild( $category->getName(), array(
                    'route' => 'products',
                    'routeParameters' => array('category' => $category->getName())
                ))
                    ->setLinkAttribute('data-toggle', 'collapse')
                    ->setLinkAttribute('onclick', 'return false;')
                    ->setLinkAttribute('data-target', '.' . $category->getId())
                    ->setChildrenAttribute('class', 'collapse ' . $category->getId());

                $this->setChildrenItem($item, $categories, $category);
            }
        }
        return $menu;
    }

    public function createSmallMenu(array $options) {
        $category1 = new Category(1, 'КОНФЕТЫ', null, 'sweets');
        $category2 = new Category(2, 'ПЕЧЕНЬЕ', null, 'cookies');
        $category3 = new Category(3, 'ПОДАРКИ', null, 'presents');
        $category22 = new Category(31, 'ЕВРОПЕЙСКИЕ СЛАДОСТИ', null, 'presents');
        $category223 = new Category(32, 'ЕЩЕ2', null, 'presents');
        $category32 = new Category(333, 'ЕЩЕ3', null, 'presents');
        $category4 = new Category(4, 'Леденцы', 1, '123');
        $category5 = new Category(5, 'Шоколадные', 1, '123');
        $category6 = new Category(6, 'Суфле', 1, '123');
        $category7 = new Category(7, 'Бисквитное', 2, '123');
        $category8 = new Category(8, 'Вафли', 2, '123');
        $category9 = new Category(9, 'Для нее', 3, '23');
        $category10 = new Category(10, 'Для него', 3, '123');
        $category11 = new Category(11, 'На новый год', 3, '123');
        $cat = new Category(100, 'fbfdfbfd', 4, '111');
        $array = [$category1, $category2, $cat, $category3, $category4, $category5, $category6,
            $category7, $category8, $category8, $category9, $category10, $category11, $category22, $category32, $category223];
        $menu = $this->factory->createItem('sidebar');

        $menu->setChildrenAttribute('class', 'navbar-nav hidden-md hidden-lg');
        foreach ($array as $category) {
            if ($category->getParent() == null) {
                $item = $menu->addChild( $category->getName(), array('route' => 'login'))
//                    ->setAttribute('class', 'dropdown');
                    ->setAttribute('class', 'dropdown')
                    ->setLinkAttribute('class', 'dropdown-toggle')
                    ->setLinkAttribute('data-toggle', 'dropdown')
                    ->setChildrenAttribute('class', 'dropdown-menu');
                $this->setChildrenItem($item, $array, $category);
            }
        }
        if ($this->container->get('session')->has('real_user_id')) {
            $menu->addChild('Deimpersonate', array('route' => 'login'));
        }
        return $menu;
    }
}