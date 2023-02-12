<?php

namespace App\Service\Stripe\Checkout;

use Psr\Log\LoggerInterface;

class StripeOrderCheckout
{
  /**
   * @param .env Variable(s) d'environnement
   */
  public function __construct(private LoggerInterface $logger)
  {
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
    return \Stripe\Checkout\Session::create([
      'customer_email' => $checkoutInfos["email"],
      "success_url" => $checkoutInfos["success_url"],
      'cancel_url' => $checkoutInfos["cancel_url"],
      'mode' => 'payment',
      'line_items' => [[
        'price' => $checkoutInfos["price"],
        'quantity' => 1,
      ]],
      'metadata' => [
        'orderId' => $checkoutInfos["orderId"],
      ],
    ])->url;
  }
}

