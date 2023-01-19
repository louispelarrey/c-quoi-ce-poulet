<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MealsVoter extends Voter
{
    public const CHANGE = 'MEAL_CHANGE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CHANGE])
            && $subject instanceof \App\Entity\Meals;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CHANGE:

                if($user->getRoles()[0] === 'ROLE_ADMIN') {
                    return true;
                }

                /**
                 * @var \App\Entity\User $user
                 */
                foreach($user->getRestaurants() as $restaurant) {
                    if($restaurant->getMeals()->contains($subject)) {
                        return true;
                    }
                }

                break;
        }

        return false;
    }
}
