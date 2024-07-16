<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use App\Repository\PurchaseOrderRepository;

class TestControllerTest extends WebTestCase
{
    private $client;
    private UserRepository $userRepository;
    private PurchaseOrderRepository $purchaseOrderRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = static::getContainer()->get(UserRepository::class);
        $this->purchaseOrderRepository = static::getContainer()->get(PurchaseOrderRepository::class);
    }

    public function testUserACanApproveSmallOrder()
    {
        $user = $this->userRepository->findOneBy(['username' => 'PersonA']);
        $purchaseOrder = $this->purchaseOrderRepository->findOneBy(['amount' => 5000]);
        self::assertNotNull($user);
        self::assertNotNull($purchaseOrder);
        $this->client->loginUser($user);

        $this->client->request('GET', '/approve/' . $purchaseOrder->getId());
        self::assertResponseRedirects('/');
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('.flash-success');
        self::assertSelectorTextSame('.flash-success', 'Access Granted');
    }

    public function testUserBCannotApproveSmallOrder()
    {
        $user = $this->userRepository->findOneBy(['username' => 'PersonB']);
        $purchaseOrder = $this->purchaseOrderRepository->findOneBy(['amount' => 5000]);
        self::assertNotNull($user);
        self::assertNotNull($purchaseOrder);
        $this->client->loginUser($user);

        $this->client->request('GET', '/approve/' . $purchaseOrder->getId());
        self::assertResponseRedirects('/');
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('.flash-notice');
        self::assertSelectorTextSame('.flash-notice', 'Access Denied.');
    }

    public function testUserCCannotApproveSmallOrder()
    {
        $user = $this->userRepository->findOneBy(['username' => 'PersonC']);
        $purchaseOrder = $this->purchaseOrderRepository->findOneBy(['amount' => 5000]);
        self::assertNotNull($user);
        self::assertNotNull($purchaseOrder);
        $this->client->loginUser($user);

        $this->client->request('GET', '/approve/' . $purchaseOrder->getId());
        self::assertResponseRedirects('/');
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('.flash-notice');
        self::assertSelectorTextSame('.flash-notice', 'Access Denied.');
    }
}
