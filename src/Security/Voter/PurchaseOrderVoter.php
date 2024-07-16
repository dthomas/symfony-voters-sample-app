<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PurchaseOrderVoter extends Voter
{
    public const APPROVE = 'approve';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::APPROVE])
            && $subject instanceof \App\Entity\PurchaseOrder;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $purchaseOrder = $subject;

        return match (true) {
            ($purchaseOrder->getAmount() < 10_000) => $user->getUserIdentifier() === 'PersonA',
            ($purchaseOrder->getAmount() < 100_000) => $user->getUserIdentifier() === 'PersonB',
            default => $user->getUserIdentifier() === 'PersonC',
        };
    }
}
