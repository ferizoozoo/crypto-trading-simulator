<?php

namespace App\Tests;

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

    // TODO: maybe this should be a general function for all tests, since it's gonna be repeated
    private function cleanUp(array $emails): void
    {
        foreach ($emails as $email) {
            $this->container->get(UserServiceInterface::class)->deleteByEmail($email);
        }
    }

    public function testWithdraw(): void
    {
        $user1 = [
            'email' => 'testWithdraw@example.com',
            'password' => "test1",
            'balance' => 100
        ];

        $this->client->request('POST', '/auth/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($user1));
        $this->assertResponseIsSuccessful();

        $this->client->request('POST', '/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($user1));
        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse()->getContent();
        $data = json_decode($response, true);
        $this->assertArrayHasKey('jwt-token', $data);
        $token = $data['jwt-token'];

        $amountToBeWithdrawn = 10;

        $this->client->request('POST', '/user/balance/withdraw', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_Authorization' => "Bearer $token"], json_encode($amountToBeWithdrawn));
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', '/user/balance', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_Authorization' => "Bearer $token"]);
        $this->assertResponseIsSuccessful();

        $balance = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($balance, $user1['balance'] - $amountToBeWithdrawn);

        $this->cleanUp([$user1['email']]);
    }

    public function testDeposit(): void
    {
        $user1 = [
            'email' => 'testDeposit@example.com',
            'password' => "test1",
            'balance' => 100
        ];

        $this->client->request('POST', '/auth/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($user1));
        $this->assertResponseIsSuccessful();

        $this->client->request('POST', '/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($user1));
        $this->assertResponseIsSuccessful();

        $response = $this->client->getResponse()->getContent();
        $data = json_decode($response, true);
        $this->assertArrayHasKey('jwt-token', $data);
        $token = $data['jwt-token'];

        $amountToBeDeposited = 10;

        $this->client->request('POST', '/user/balance/deposit', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_Authorization' => "Bearer $token"], json_encode($amountToBeDeposited));
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', '/user/balance', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_Authorization' => "Bearer $token"]);
        $this->assertResponseIsSuccessful();

        $balance = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($balance, $user1['balance'] + $amountToBeDeposited);

        $this->cleanUp([$user1['email']]);
    }
}
