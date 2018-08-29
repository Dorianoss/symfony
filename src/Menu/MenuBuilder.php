<?php

namespace App\Menu;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Routing\RouterInterface;

class MenuBuilder
{
    private $factory;
    private $categoryRepository;
    private $list;
    private $router;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     * @param CategoryRepository $categoryRepository
     * @param RouterInterface $router
     */
    public function __construct(FactoryInterface $factory,
                                CategoryRepository $categoryRepository,
                                RouterInterface $router)
    {
        $this->factory = $factory;
        $this->categoryRepository = $categoryRepository;
        $this->router = $router;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        return $this->makeTree($menu);
    }

    /**
     * @param $menu
     * @return ItemInterface
     */
    public function makeTree($menu)
    {
        $list = $this->categoryRepository->findAll();
        $parentId = null;
        return $this->buildTree($list, $menu, $parentId);
    }

    /**
     * @param Category[] $list
     * @param ItemInterface $menu
     * @param $parentId
     * @return ItemInterface
     */
    public function buildTree(array $list, $menu, $parentId)
    {
        foreach ($list as $value) {
            if (
                ($value->getParent() && $value->getParent()->getId() == $parentId)
                || ($value->getParent() === null && $parentId === null)
            )   {
                $children = $menu->addChild($value->getId(), ['label' => $value->getName(), 'uri' => $this->router->generate('category_list', [
                    'id' => $value->getId()
                ])]);
                $this->buildTree($list, $children, $value->getId());
                unset($list[$value->getId()]);
            }
        }

        return $menu;
    }
}