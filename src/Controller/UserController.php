<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;

class UserController extends Controller
{
    /**
     * @Route("/user/add", name="app_user_add")
     * @param User $user
     * @return Response
     * @throws \Exception
     */
    public function addUser(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $email=$user->getEmail();

        $userTest = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);
        if ($userTest) {
            throw new \Exception(
                'There is already a user with email ' . $email
            );
        } else {
            // tell Doctrine you want to (eventually) save the User (no queries yet)
            $entityManager->persist($user);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new Response('Saved new user with id ' . $user->getId());
        }
    }

    /**
     * @Route("/user/login", name="app_user_login")
     * @param User $user
     * @return Response
     * @throws \Exception
     */
    public function login(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $email=$user->getEmail();
        $password=$user->getPassword();

        $userTest = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['email' => $email,'password' => $password]);
        if (!$userTest) {
            throw new \Exception(
                'Check your login and password'
            );
        } else {
            // tell Doctrine you want to (eventually) save the User (no queries yet)
            $entityManager->persist($user);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new Response('Successful logging in' . $user->getId());
        }
    }

        }