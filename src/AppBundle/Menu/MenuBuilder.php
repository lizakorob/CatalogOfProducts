<?php

namespace AppBundle\Menu;

use AppBundle\Entity\Category;
use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function setChildrenItem($item, array $array, Category $currentCategory)
    {
        foreach ($array as $category) {
            if ($category->getParent() === $currentCategory->getId()) {
                $item->addChild($category->getName(), array('route' => 'register'
                    /*'catalog/'.$currentCategory->getUrl().$category->getUrl()*/));
            }
        }
    }

    public function createMainMenu(array $options)
    {
        $category1 = new Category(1, 'КОНФЕТЫ', null, 'sweets');
        $category2 = new Category(2, 'ПЕЧЕНЬЕ', null, 'cookies');
        $category3 = new Category(3, 'ПОДАРКИ', null, 'presents');
        $category4 = new Category(4, 'Леденцы', 1, '123');
        $category5 = new Category(5, 'Шоколадные', 1, '123');
        $category6 = new Category(6, 'Суфле', 1, '123');
        $category7 = new Category(7, 'Бисквитное', 2, '123');
        $category8 = new Category(8, 'Вафли', 2, '123');
        $category9 = new Category(9, 'Для нее', 3, '23');
        $category10 = new Category(10, 'Для него', 3, '123');
        $category11 = new Category(11, 'На новый год', 3, '123');
        $cat = new Category(100, 'fbfdfbfd', null, '111');
        $array = [$category1, $category2, $cat, $category3, $category4, $category5, $category6,
            $category7, $category8, $category8, $category9, $category10, $category11];

        $menu = $this->factory->createItem('main');
//        $menu->setChildrenAttribute('class', 'nav navbar-nav');
        foreach ($array as $category) {
            if ($category->getParent() == null) {
                $item = $menu->addChild($category->getName(), array('route' => 'login'/*'catalog/'.$category->getUrl()*/))
                    ->setAttribute('class', 'dropdown')
                    ->setLinkAttribute('data-toggle', 'dropdown')
                    ->setLinkAttribute('class', 'dropdown-toggle')
                    ->setChildrenAttribute('class', 'dropdown-menu')
                    ->setChildrenAttribute('role', 'menu');
                    $this->setChildrenItem($item, $array, $category);
            }
        }
        return $menu;
    }

    public function createSidebarMenu(array $options)
    {
        $menu = $this->factory->createItem('sidebar');

        if (isset($options['include_homepage']) && $options['include_homepage']) {
            $menu->addChild('Home', array('route' => '/123'));
        }

        return $menu;
    }
}