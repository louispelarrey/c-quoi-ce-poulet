<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\User;

class JWTCreatedListener
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onLexikJwtAuthenticationOnJwtCreated(JWTCreatedEvent $event)
    {
        $payload       = $event->getData();
        /**
         * @var User $user
         */
        $user = $event->getUser();
        $payload['user_id'] = $user->getId();

        $event->setData($payload);
    }
}
