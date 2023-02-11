<?php

namespace App\Service\Stripe\Webhook;

class StripeOrderWebhook extends AbstractWebhook
{
  /**
   * https://dashboard.stripe.com/test/webhooks/create?endpoint_location=local
   *
   * stripe listen --forward-to localhost:80/webhook/checkout/index
   * stripe trigger checkout.session.completed
   */
  protected function treatPayment(string $email, string $orderId)
  {
    $this->handlePayment($email, $orderId);
  }

  function handlePayment(string $email, string $orderId)
  {
    $this->logger->notice("Paiement ok $email, orderId $orderId");

    $order = $this->orderRepository->findOneBy(['id' => $orderId]);

    if ($order) {
      $order->setStatus(['status' => 'paid']);
      $this->em->persist($order);
      $this->em->flush();
      $this->mailer->sendMail(
        $email,
        'Votre commande est validée',
        "Vous recevrez votre commande dans les plus brefs délais. Merci de votre confiance !",
      );
    }
  }
}

