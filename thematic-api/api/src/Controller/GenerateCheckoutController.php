<?php

namespace App\Controller;

use App\Service\Stripe\Checkout\StripeOrderCheckout;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/generate-checkout', name: 'generate_checkout', methods: ['POST'])]
class GenerateCheckoutController extends AbstractController
{
    public function __construct(
        private StripeOrderCheckout $stripeOrderCheckout
    ) {
    }


    public function __invoke(Request $request): JsonResponse
    {
        if($request->getContent() === null) {
            throw new \Exception("No content");
        }

        if($request->getContentType() !== "json") {
            throw new \Exception("Content type must be json");
        }

        $infos = json_decode($request->getContent(), true);

        return $this->json([
            "url" => $this->stripeOrderCheckout->createCheckout([
                "email" => $infos["email"],
                "orderId" => $infos["orderId"],
                "successUrl" => $infos["successUrl"],
                "cancelUrl" => $infos["cancelUrl"],
            ])
        ]);
    }
}
