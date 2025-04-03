<?php

namespace App\Tests;

use App\Controller\Services\UserServiceInterface;
use App\Entities\Order;
use App\Entities\Trade;
use App\Entities\User;
use App\Enums\OrderType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// TODO: for the test, maybe setting up tokens for the users would be better, for most test classes
class TradeTest extends WebTestCase
{
    private $container;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        self::bootKernel();
        $this->container = static::getContainer();
    }

    public function testCorrectBalanceForUserAfterTrade(): void
    {
        $buyer = new User('buyer@example.com', 'passwordbuyer');
        $seller = new User('seller@example.com', 'passwordseller');

        $buyerInitialBalance = 1000;
        $sellerInitialBalance = 1000;

        $buyer->setBalance($buyerInitialBalance);
        $seller->setBalance($sellerInitialBalance);

        // create buyer and seller users
        $this->container->get(UserServiceInterface::class)->register($buyer);
        $this->container->get(UserServiceInterface::class)->register($seller);

        $buyer = $this->container->get(UserServiceInterface::class)->findByEmail($buyer->getEmail());
        $seller = $this->container->get(UserServiceInterface::class)->findByEmail($seller->getEmail());

        $buyOrder = [
            'userId' => $buyer->getId(),
            'amount' => 10,
            'price' => 100,
            'type' => OrderType::BUY->value,
            'timestamp' => new DateTime()->getTimestamp(),
        ];
        $sellOrder = [
            'userId' => $seller->getId(),
            'amount' => 10,
            'price' => 100,
            'type' => OrderType::SELL->value,
            'timestamp' => new DateTime()->getTimestamp(),
        ];

        $expectedTrade = new Trade($buyer->getId(), $seller->getId(), 100, 10, new DateTime());

        // get the token for the users
        $buyerToken = $this->container->get(UserServiceInterface::class)->authenticate($buyer);
        $sellerToken = $this->container->get(UserServiceInterface::class)->authenticate($seller);

        // place orders
        $this->client->request('POST', '/order', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer ' . $sellerToken,], json_encode($sellOrder));
        $this->client->request('POST', '/order', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer ' . $buyerToken,], json_encode($buyOrder));
        $this->assertResponseIsSuccessful();

        // get the last trade response
        $response = $this->client->getResponse()->getContent();
        $actualTrade = json_decode($response, true);
        $this->assertEquals($expectedTrade->getBuyerId(), $actualTrade['buyerId']);
        $this->assertEquals($expectedTrade->getSellerId(), $actualTrade['sellerId']);
        $this->assertEquals($actualTrade['price'], $expectedTrade->getPrice());
        $this->assertEquals($actualTrade['amount'], $expectedTrade->getAmount());

        // check balance for buyer and seller after trade
        $this->client->request('GET', '/user/balance', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer ' . $buyerToken], json_encode($buyer->getId()));
        $buyerBalance = $this->client->getResponse()->getContent();
        $this->assertEquals($buyerInitialBalance - $actualTrade['price'] * $actualTrade['quantity'], json_decode($buyerBalance, true));

        $this->client->request('GET', '/user/balance', [], [], ['CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer ' . $sellerToken], json_encode($seller->getId()));
        $sellerBalance = $this->client->getResponse()->getContent();
        $this->assertEquals($sellerInitialBalance - $actualTrade['price'] * $actualTrade['quantity'], json_decode($sellerBalance, true));
    }
}
