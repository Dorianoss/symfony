<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user")
     * @param string $email
     * @param string $password
     * @return Response
     * @throws \Exception
     */
    public function addUser(string $email, string $password)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);

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
     * @Route("/user", name="user")
     * @param string $email
     * @param string $password
     * @return Response
     * @throws \Exception
     */
    public function login(string $email, string $password)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);

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