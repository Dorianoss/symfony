<?php

namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class UserService
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * UserService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em=$entityManager;
    }

    /**
     * @param User $user
     * @return User
     * @throws \Exception
     */
    public function register(User $user) :User
    {

        $email=$user->getEmail();

        $userTest = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);
        if ($userTest) {
            throw new \Exception(
                'There is already a user with email ' . $email
            );
        }
        else {
            // tell Doctrine you want to (eventually) save the User (no queries yet)
            $this->em->persist($user);

            // actually executes the queries (i.e. the INSERT query)
            $this->em->flush();

            return $user;
        }
    }
}