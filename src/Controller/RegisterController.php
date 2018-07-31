<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends Controller
{
    /**

     * @param Request $request
     * @param UserService $userService
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request, UserService $userService)
    {
        $form = $this->createForm(RegisterType::class, new User);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();
            $userService->register($user);
            return new Response('Saved new user with id ' . $user->getId());
        }


        return $this->render('register.html.twig', ['our_form' => $form->createView()]);
    }
}
