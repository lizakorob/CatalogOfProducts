<?php

namespace AppBundle\Menu;

use AppBundle\Entity\Category;
use Knp\Menu\FactoryInterface;
use function PHPSTORM_META\map;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MenuBuilder
{
    private $factory;
    private $container;

    public function __construct(FactoryInterface $factory, ContainerInterface $container)
    {
        $this->factory = $factory;
        $this->container = $container;
    }

    public function setChildrenItem($item, array $array, Category $currentCategory)
    {
        foreach ($array as $category) {
            if ($category->getParent() === $currentCategory->getId()) {
                $item->addChild($category->getName(), array(
                    'route' => 'products',
                    'routeParameters' => array(
                        'category' => $category->getName())))->getParent();
                //$this->setChildrenItem($item, $array, $category);
            }
        }
    }

    public function createMainMenu(array $options)
    {
        $menuItems = array (
//            '/' => 'Главная',
            'products' => 'Каталог',
//            'about' => 'О нас',
        );
        $menu = $this->factory->createItem('main');
        $menu->setChildrenAttribute('class', 'nav navbar-nav mainMenu col-md-12 hidden-xs hidden-sm');
        foreach ($menuItems as $ref => $value) {
            $menu->addChild($value, array(
                'route' => $ref,
                ));
        }
        if ($options['user'] != 'ROLE_USER') {
            $menu->addChild('Управление категориями', array(
                'route' => 'categories',
            ));
        }
        if ($options['user'] == 'ROLE_ADMIN') {
            $menu->addChild('Управление пользователями', array(
                'route' => 'users',
            ));
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
        $menu->setChildrenAttribute('class', 'nav navbar-nav');
        foreach ($array as $category) {
            if ($category->getParent() == null) {
                $item = $menu->addChild( $category->getName(), array(
                    'route' => 'login',
                    'routeParameters' => array('category' => $category->getName())))
                    ->setAttribute('class', 'dropdown')
                    ->setLinkAttribute('class', 'dropdown-toggle')
                    ->setLinkAttribute('data-toggle', 'dropdown')
                    ->setChildrenAttribute('class', 'dropdown-menu');
                $this->setChildrenItem($item, $array, $category);
            }
        }
        return $menu;
    }
}