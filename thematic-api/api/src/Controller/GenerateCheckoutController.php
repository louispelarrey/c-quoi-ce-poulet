<?php

namespace App\Controller;

use App\Service\Stripe\Checkout\StripeOrderCheckout;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GenerateCheckoutController extends AbstractController
{
    public function __construct(
        private StripeOrderCheckout $stripeOrderCheckout
    ) {
    }

    // public function __invoke(): array
    // {
    //     return [
    //         "url" => $this->stripeOrderCheckout->createCheckout([
    //             "email" => "
    // }
}
