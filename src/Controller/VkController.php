<?php

namespace App\Controller;

use App\Form\VkType;
use App\Service\VkAPI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        $type = $request->getSession()->get('type');
        if ($type) {
            $data = $vkAPI->getData($type);
            if ($data === false) {
                return $this->redirectToRoute('vk_code');
            }
        }

        $form = $this->createForm(VkType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vk = $form->getData();
            $request->getSession()->set('type', $vk["type"]);
            $type = $request->getSession()->get('type');
            $data = $vkAPI->getData($type);
            $request->getSession()->set('data', $data);
            return $this->redirectToRoute('vk_show');
        }



        return $this->render('vk/vk_form.html.twig', array(
            'form' => $form->createView(),
        ));

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

    /**
     * @Route("/vk/show", name="vk_show")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function vkShow(Request $request)
    {
        $data = $request->getSession()->get('data');
        $type = $request->getSession()->get('type');
//        dump($data);
//        die;
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
