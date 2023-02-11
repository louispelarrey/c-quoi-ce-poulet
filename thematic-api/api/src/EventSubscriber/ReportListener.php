<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Report;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReportListener implements EventSubscriberInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => 
                ['setReportedBy', EventPriorities::PRE_WRITE],
        ];
    }

    public function setReportedBy(ViewEvent $event)
    {
        $report = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$report instanceof Report || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();
        $report->setReportedBy($user);
    }
}
