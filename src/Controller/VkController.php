<?php

namespace App\Controller;

use App\Entity\Vk;
use App\Service\VkAPI;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

        $vk = new Vk();
        $form = $this->createFormBuilder($vk)
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'Friend' => "friend",
                    'Photo' => "photo",
                    'Video' => "video",
                ),
                'expanded' => true,
                'multiple' => false,
            ))
            ->add('save', SubmitType::class, array('label' => 'Get data'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            dump($vk);


            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

        }
        return $this->render('vk/vk.html.twig', array(
            'form' => $form->createView(),
        ));

//        return $this->render('vk/vk.html.twig', array(
//            'form' => $form->createView(),
//        ));
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
