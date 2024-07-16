<?php

namespace App\DataFixtures;

use App\Entity\PurchaseOrder;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Create users

        $password = $this->passwordHasher->hashPassword(new User(), 'secret');

        $requester = new User();
        $requester->setUsername('Purchaser');
        $requester->setRoles(['ROLE_PURCHASER']);
        $requester->setPassword($password);
        $manager->persist($requester);

        $userA = new User();
        $userA->setUsername('PersonA');
        $userA->setRoles(['ROLE_MANAGER']);
        $userA->setPassword($password);
        $manager->persist($userA);

        $userB = new User();
        $userB->setUsername('PersonB');
        $userB->setRoles(['ROLE_MANAGER']);
        $userB->setPassword($password);
        $manager->persist($userB);

        $userC = new User();
        $userC->setUsername('PersonC');
        $userC->setRoles(['ROLE_DIRECTOR']);
        $userC->setPassword($password);
        $manager->persist($userC);

        // Create purchase orders
        $purchaseOrder1 = new PurchaseOrder();
        $purchaseOrder1->setTitle('Purchase Order #1');
        $purchaseOrder1->setRequester('requester');
        $purchaseOrder1->setAmount(5000);
        $manager->persist($purchaseOrder1);

        $purchaseOrder2 = new PurchaseOrder();
        $purchaseOrder2->setTitle('Purchase Order #2');
        $purchaseOrder2->setRequester('requester');
        $purchaseOrder2->setAmount(15000);
        $manager->persist($purchaseOrder2);

        $purchaseOrder3 = new PurchaseOrder();
        $purchaseOrder3->setTitle('Purchase Order #3');
        $purchaseOrder3->setRequester('requester');
        $purchaseOrder3->setAmount(150000);
        $manager->persist($purchaseOrder3);

        $manager->flush();
    }
}
