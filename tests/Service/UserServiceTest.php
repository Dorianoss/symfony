<?php

use App\Entity\User;
use App\Repository\UsersRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class UserServiceTest extends TestCase
{
    public function testRegister()
    {
        $user = $this->prophesize(User::class);
        $user->getEmail()->willReturn('ferdinatrastet@gmail.com')->shouldBeCalled();

        $repo = $this->prophesize(UsersRepository::class);
        $repo->findOneBy(['email' => 'ferdinatrastet@gmail.com'])->shouldBeCalled();

        $em=$this->prophesize(EntityManagerInterface::class);
        $em->getRepository(User::class)->willReturn($repo->reveal())->shouldBeCalled();
        $em->persist(Argument::type(User::class))->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $userService = new UserService($em->reveal());
        $registeredUser = $userService->register($user->reveal());

        $this->assertInstanceOf(User::class, $registeredUser);
    }

    /**
     * @expectedException Exception
     */
    public function testRegisterException()
    {
        $user = $this->prophesize(User::class);
        $user->getEmail()->willReturn('ferdinatrastet@gmail.com')->shouldBeCalled();

        $repo = $this->prophesize(UsersRepository::class);
        $repo->findOneBy(['email' => 'ferdinatrastet@gmail.com'])->willReturn($user->reveal())->shouldBeCalled();

        $em=$this->prophesize(EntityManagerInterface::class);
        $em->getRepository(User::class)->willReturn($repo->reveal())->shouldBeCalled();
        $em->persist(Argument::type(User::class));

        $userService = new UserService($em->reveal());
        $registeredUser = $userService->register($user->reveal());
        $this->assertInstanceOf(User::class, $registeredUser);
    }
}