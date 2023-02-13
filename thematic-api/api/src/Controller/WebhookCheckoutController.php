<?php

namespace App\Controller;

use App\Service\Stripe\Webhook\StripeOrderWebhook;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/webhook/checkout/index', name: 'webhook', methods: ['POST'])]
class WebhookCheckoutController extends AbstractController
{
    public function __construct(
        private StripeOrderWebhook $stripeOrderWebhook
    ) {
    }

    public function __invoke(Request $request): Response
    {
        throw new \Exception("abc");
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Allow: GET, POST, OPTIONS, PUT, DELETE");

        return $this->stripeOrderWebhook->managePayment($request) ?
            new Response(Response::HTTP_OK) : new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

