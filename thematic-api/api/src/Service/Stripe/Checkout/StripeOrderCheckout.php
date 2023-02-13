<?php

namespace App\Service\Stripe\Checkout;

use App\Repository\OrderRepository;
use Psr\Log\LoggerInterface;

class StripeOrderCheckout
{
    /**
     * @param .env Variable(s) d'environnement
     */
    public function __construct(
        private LoggerInterface $logger,
        private OrderRepository $orderRepository
    ) {
        \Stripe\Stripe::setApiKey($_ENV["STRIPE_API_KEY"]);
    }
    /**
     * Permet de générer une page de paiement Stripe Checkout
     *
     * On utilise cette méthode via parent::createCheckout(), puis on entre les infos que l'on souhaite en paramètre
     *
     */
    public function createCheckout(array $checkoutInfos): string
    {
        $orderPriceDatabase = $this->orderRepository->find($checkoutInfos["orderId"])->getTotalPrice();

        return \Stripe\Checkout\Session::create([
            'customer_email' => $checkoutInfos["email"],
            "success_url" => $checkoutInfos["successUrl"],
            'cancel_url' => $checkoutInfos["cancelUrl"],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => "Paiement C quoi ce poulet",
                    ],
                    'unit_amount' => $orderPriceDatabase * 100,
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                'orderId' => $checkoutInfos["orderId"],
            ],
        ])->url;
    }

    public function checkStripePrice(int $orderId, int $checkoutPrice): bool
    {
        $orderPriceDatabase = $this->orderRepository->find($orderId)->getTotalPrice();
        return $orderPriceDatabase === $checkoutPrice;
    }
}
