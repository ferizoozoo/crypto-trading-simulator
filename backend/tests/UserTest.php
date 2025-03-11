<?php

use App\Controller\Services\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    private $container;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        self::bootKernel();
        $this->container = static::getContainer();
    }

    public function testRegister(): void
    {
        $randomString = bin2hex(random_bytes(10));

        $user = [
            'email' => "$randomString@example.com",
            'password' => "$randomString",
        ];
        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($user));
        $this->assertResponseIsSuccessful();
    }

    public function testDuplicateEmails(): void
    {
        $email = "test@example.com";
        $user1 = [
            'email' => $email,
            'password' => "test1",
        ];

        $userWithThisEmail = $this->container->get(UserServiceInterface::class)->findByEmail($email);
        if ($userWithThisEmail !== null) {
            $this->container->get(UserServiceInterface::class)->deleteByEmail($email);
        }

        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($user1));
        $this->assertResponseIsSuccessful("User registered successfully");

        $user2 = [
            'email' => $email,
            'password' => "test2",
        ];

        $this->client->request('POST', '/user/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($user2));
        $this->assertResponseStatusCodeSame(400);
    }
}
