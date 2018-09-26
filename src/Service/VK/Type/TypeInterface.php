<?php


namespace App\Service\VK\Type;


interface TypeInterface
{
    /**
     * @return string
     */
    public function getName() : string;

    public function getData();
}