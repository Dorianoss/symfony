<?php


namespace App\Service\VK;


use App\Service\VK\Type\TypeInterface;

class VKTypeRegistry
{
    /**
     * @var TypeInterface[]
     */
    protected $types;

    public function fetch($type)
    {
        return $this->types[$type]->getData();
    }

}