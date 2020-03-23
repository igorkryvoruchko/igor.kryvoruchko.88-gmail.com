<?php


namespace App\Service;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;


class Menu
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', ['route' => 'homepage']);
        $menu->addChild('Products', ['route' => 'product_index']);
        $menu->addChild('Add Columns', ['route' => 'columns_index']);
        // ... add more children

        return $menu;
    }
}