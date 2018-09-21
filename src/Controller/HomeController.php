<?php

namespace App\Controller;

use App\Service\VkAPI;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/home", name="home")
     * @param VkAPI $vkAPI
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function indexAction(VkAPI $vkAPI)
    {
        $friend=$vkAPI->getFriends();
        dump($friend);
        die();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
