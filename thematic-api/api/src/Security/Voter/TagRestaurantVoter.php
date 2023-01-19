<?php

namespace App\Security\Voter;

use App\Entity\Restaurant;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TagRestaurantVoter extends Voter
{
    public const CHANGE = 'TAG_RESTAURANT_CHANGE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CHANGE])
            && $subject instanceof \App\Entity\TagRestaurant;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access


        if (!$user instanceof Restaurant) {
            dd($user);

            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CHANGE:
                // logic to determine if the user can EDIT
                // return true or false
                break;
        }

        return false;
    }
}
