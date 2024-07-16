<?php

namespace App\Tests\Security\Voter;

use App\Entity\PurchaseOrder;
use App\Security\Voter\PurchaseOrderVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PurchaseOrderVoterTest extends TestCase
{
    public function testVoteOnAttribute()
    {
        $voter = new PurchaseOrderVoter();

        $purchaseOrder = new PurchaseOrder();
        $purchaseOrder->setAmount(5000);

        $userA = $this->createUser('PersonA');
        $userB = $this->createUser('PersonB');
        $userC = $this->createUser('PersonC');

        $tokenA = $this->createToken($userA);
        $tokenB = $this->createToken($userB);
        $tokenC = $this->createToken($userC);

        $method = new \ReflectionMethod(PurchaseOrderVoter::class, 'voteOnAttribute');
        $method->setAccessible(true);

        // Test for amount < 10,000
        $this->assertTrue($method->invoke($voter, PurchaseOrderVoter::APPROVE, $purchaseOrder, $tokenA));
        $this->assertFalse($method->invoke($voter, PurchaseOrderVoter::APPROVE, $purchaseOrder, $tokenB));
        $this->assertFalse($method->invoke($voter, PurchaseOrderVoter::APPROVE, $purchaseOrder, $tokenC));

        // Test for amount between 10,000 and 100,000
        $purchaseOrder->setAmount(15000);
        $this->assertFalse($method->invoke($voter, PurchaseOrderVoter::APPROVE, $purchaseOrder, $tokenA));
        $this->assertTrue($method->invoke($voter, PurchaseOrderVoter::APPROVE, $purchaseOrder, $tokenB));
        $this->assertFalse($method->invoke($voter, PurchaseOrderVoter::APPROVE, $purchaseOrder, $tokenC));

        // Test for amount > 100,000
        $purchaseOrder->setAmount(150000);
        $this->assertFalse($method->invoke($voter, PurchaseOrderVoter::APPROVE, $purchaseOrder, $tokenA));
        $this->assertFalse($method->invoke($voter, PurchaseOrderVoter::APPROVE, $purchaseOrder, $tokenB));
        $this->assertTrue($method->invoke($voter, PurchaseOrderVoter::APPROVE, $purchaseOrder, $tokenC));
    }

    private function createUser(string $username): UserInterface
    {
        $user = $this->createMock(UserInterface::class);
        $user->method('getUserIdentifier')->willReturn($username);

        return $user;
    }

    private function createToken(UserInterface $user): TokenInterface
    {
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($user);

        return $token;
    }
}
