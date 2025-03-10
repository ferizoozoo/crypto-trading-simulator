<?php

use App\Controller\Services\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entities\User;

class UserTest extends KernelTestCase
{
    private $container;

    public function setUp(): void
    {
        self::bootKernel();
        $this->container = self::getContainer();
    }

    public function testRegister(): void
    {
        $user = new User("GgYwH@example.com", "password");

        $result = $this->container->get(UserServiceInterface::class)->register($user);

        $this->assertTrue($result);
    }
}
