<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Service\Mail\Mailer;
use App\Service\Stripe\Webhook\StripeOrderWebhook;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/webhook/checkout/index/{id}', name: 'webhook', methods: ['POST'])]
class WebhookCheckoutController extends AbstractController
{
    public function __construct(
        // private StripeOrderWebhook $stripeOrderWebhook,
        private OrderRepository $orderRepository,
        private EntityManagerInterface $em,
        private Mailer $mailer,
    ) {
    }

    public function __invoke(int $id): JsonResponse
    {
        $order = $this->orderRepository->findOneBy(['id' => $id]);
        $email = $order->getClient()->getEmail();

        if ($order) {
            $order->setStatus("paid");
            $this->em->persist($order);
            $this->em->flush();
            $this->mailer->sendMail(
                $email,
                'Votre commande est validée',
                "Vous recevrez votre commande dans les plus brefs délais. Merci de votre confiance !",
            );

            return $this->json("ok");
        }

        return $this->json("ok");

        // header('Access-Control-Allow-Origin: *');
        // header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        // header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        // header("Allow: GET, POST, OPTIONS, PUT, DELETE");

        // return $this->stripeOrderWebhook->managePayment($request) ?
        //     new Response(Response::HTTP_OK) : new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

