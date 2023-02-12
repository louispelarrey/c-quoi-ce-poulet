<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class OrderVoter extends Voter
{
    public const GET = 'ORDER_GET';
    public const CAN_DELIVER = 'ORDER_CAN_DELIVER';
    public const CAN_ACCEPT = 'ORDER_CAN_ACCEPT';

    public function __construct(
        private readonly Security $security,
    ){}

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CAN_DELIVER, self::GET, self::CAN_ACCEPT])
            && $subject instanceof \App\Entity\Order;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($this->security->isGranted('ROLE_ADMIN')){
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::GET:
                /**
                 * @var User $user
                 * @var Order $subject
                 */
                if($user->getOrders()->contains($subject) || $user->getDeliveryOrders()->contains($subject) || $user->getRestaurantOrders()->contains($subject) || $user->getRoles()[0] === 'ROLE_ADMIN'){
                    return true;
                }
                break;

            case self::CAN_ACCEPT:
                /**
                 * @var User $user
                 * @var Order $subject
                 */
                // Can deliver if the user is a deliveryman and the order is in "paid" status and the user is the deliveryman of the order
                if($user->getRoles()[0] === 'ROLE_DELIVERER' && $subject->getStatus() === 'paid'){
                    return true;
                }
                break;
            case self::CAN_DELIVER:
                /**
                 * @var User $user
                 * @var Order $subject
                 */
                // Can deliver if the user is a deliveryman and the order is in "paid" status and the user is the deliveryman of the order
                if($user->getRoles()[0] === 'ROLE_DELIVERER' && $subject->getStatus() === 'accepted' && $user->getDeliveryOrders()->contains($subject)){
                    return true;
                }
                break;
        }

        return false;
    }
}
