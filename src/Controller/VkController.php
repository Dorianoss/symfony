<?php

namespace App\Controller;

use App\Form\VkType;
use App\Service\VK\VKTypeRegistry;
use App\Service\VkAPI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VkController extends Controller
{
    /**
     * @Route("/vk", name="vk")
     *
     * @param VkAPI $vkAPI
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function indexAction(VkAPI $vkAPI, Request $request)
    {
        $form = $this->createForm(VkType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vk = $form->getData();
            $type = $vk["type"];
            return $this->redirectToRoute('vk_show', ['type' => $type]);
        }

        return $this->render('vk/vk_form.html.twig', array(
            'form' => $form->createView(),
        ));

        }


    /**
     * @Route("/vk/code/{type}", name="vk_code")
     *
     * @param Request $request
     * @param VkAPI   $vkAPI
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \VK\Exceptions\VKClientException
     * @throws \VK\Exceptions\VKOAuthException
     */
    public function vkAction($type, Request $request, VkAPI $vkAPI)
    {
        $code = $request->get('code');

        if (!$code) {
            return $this->redirect($vkAPI->authorize());
        }

        $token = $vkAPI->getToken($code);

        $request->getSession()->set('vk_token', $token);

        return $this->redirectToRoute('vk_show', ['type' => $type]);
    }

    /**
     * @Route("/vk/show/{type}", name="vk_show")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function vkShow($type, Request $request, VKTypeRegistry $vkType)
    {
        if ($type) {
            $data = $vkType->fetch($type);
            if ($data === false) {
                return $this->redirectToRoute('vk_code', ['type' => $type]);
            }
        }
        switch ($type) {
            case "friend":
                return $this->render('vk/friend.html.twig', ['name' => $data[0]["first_name"], 'surname' =>
                    $data[0]["last_name"]]);
                break;
            case "photo":
                return $this->render('vk/photo.html.twig', ['photo' => $data]);
                break;
            case "video":
                return $this->render('vk/video.html.twig', ['video' => $data]);
                break;
        }




    }

}
