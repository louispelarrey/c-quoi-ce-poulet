<?php

namespace App\EventSubscriber;

use App\Service\Mail\Mailer;
use CoopTilleuls\ForgotPasswordBundle\Event\CreateTokenEvent;
use CoopTilleuls\ForgotPasswordBundle\Event\UpdatePasswordEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ForgotPasswordEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly Mailer $mailer,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UpdatePasswordEvent::class => 'onUpdatePassword',
            CreateTokenEvent::class => 'onCreateToken',
        ];
    }

    public function onUpdatePassword(UpdatePasswordEvent $event): void
    {
        $passwordToken = $event->getPasswordToken();
        $user = $passwordToken->getUser();
        $user->setPlainPassword($event->getPassword());
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
        $user->eraseCredentials();
    }

    public function onCreateToken(CreateTokenEvent $event): void
    {
        $passwordToken = $event->getPasswordToken();
        $user = $passwordToken->getUser();

        $this->mailer->sendMail(
            $user->getEmail(),
            "Réinitialisation de votre mot de passe",
            "Cliquez ici pour réinitialiser votre mot de passe : " . $_ENV["FRONT_URL"] . "/reset-password/" . $passwordToken->getToken()
        );
    }
}
