<?php

use App\Controller\Services\UserServiceInterface;
use App\Entities\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    private $container;
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->container = static::getContainer();
    }

    public function testRegister(): void
    {
        $randomString = bin2hex(random_bytes(10));
        // $user = new User("$randomString@example.com", "$randomString");

        $user = [
            'email' => "$randomString@example.com",
            'password' => "$randomString",
        ];
        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($user));
        $this->assertResponseIsSuccessful();
    }
}
