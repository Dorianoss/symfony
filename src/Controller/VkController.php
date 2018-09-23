<?php

namespace App\Controller;

use App\Service\VkAPI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use VK\Client\VKApiClient;

class VkController extends Controller
{
    /**
     * @Route("/vk", name="vk")
     *
     * @param VkAPI   $vkAPI
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \VK\Exceptions\VKApiException
     * @throws \VK\Exceptions\VKClientException
     */
    public function indexAction(VkAPI $vkAPI, Request $request)
    {

        $friends = $vkAPI->getFriends();

        if ($friends === false) {
            return $this->redirectToRoute('vk_code');
        }

        dump($friends);

        die;
    }

    /**
     * @Route("/vk/code", name="vk_code")
     *
     * @param Request $request
     * @param VkAPI   $vkAPI
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \VK\Exceptions\VKClientException
     * @throws \VK\Exceptions\VKOAuthException
     */
    public function vkAction(Request $request, VkAPI $vkAPI)
    {
        $code = $request->get('code');

        if (!$code) {
            return $this->redirect($vkAPI->authorize());
        }

        $token = $vkAPI->getToken($code);

        $request->getSession()->set('vk_token', $token);

        return $this->redirectToRoute('vk');
    }


}