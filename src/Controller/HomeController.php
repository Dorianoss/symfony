<?php

namespace App\Controller;

use App\Service\VkAPI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use VK\Client\VKApiClient;

class HomeController extends Controller
{
    /**
     * @Route("/home", name="home")
     * @param VkAPI $vkAPI
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(VkAPI $vkAPI, Request $request)
    {
        $code = $request->get('code');
        if (!$code) {
            $authorize = $vkAPI->authorize();
            header('Location: ' . $authorize);
        }
        else{
            $token = $vkAPI->getToken($code);
            $friends = $vkAPI->getFriends($token);
            dump($friends);
        }
        die;
//        return $this->render('home/index.html.twig', [
//            'controller_name' => 'HomeController',
//        ]);
    }
}
