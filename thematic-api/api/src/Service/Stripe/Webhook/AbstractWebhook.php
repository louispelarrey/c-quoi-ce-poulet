<?php

namespace App\Service\Stripe\Webhook;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Mail\Mailer;

/**
 * Le but de cette classe abstraite est de définir le comportement général des classes filles et d'industrialiser le développement de pages de paiement
 *  pour d'autres clients en ayant simplement à modifier le code "variable"
 *
 * Il suffira donc de recopier le fonctionnement des classes contenues dans le dossier /Checkout pour générer d'autres systèmes de paiement
 */
abstract class AbstractWebhook
{
  /**
   * @param .env Variable(s) d'environnement
   */
  public function __construct(protected LoggerInterface $logger, protected EntityManagerInterface $em,
    protected OrderRepository $orderRepository, protected Mailer $mailer)
  {
    \Stripe\Stripe::setApiKey($_ENV["STRIPE_API_KEY"]);
  }

  /**
   * Gère le webhook de paiement
   */
  public function managePayment(Request $request): bool
  {
    $event = null;

    try {
      $event = $this->generateEvent(
        @file_get_contents('php://input'),
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'],
      );
    } catch (\UnexpectedValueException $e) {
      $this->logUnexpectedValue($e);
      return false;
    } catch (SignatureVerificationException $e) {
      $this->logSignatureException($e);
      return false;
    }

    $event->type == "checkout.session.completed" ? $this->payComplete($event, $request) : $this->logUnkownComplete($event);
    return true;
  }

  /**
   * Lorsqu'un utilisateur finit son paiement
   */
  protected function payComplete(Event $event, Request $request): void
  {
    $session = $event->data->object;
    $this->logger->notice("[Webhook] - Received checkout : " . $session->client_reference_id);

    if ($session->payment_status == "paid") {
      //TODO: Mettre ici le code exécuté lorsqu'un paiement est payé
      $this->treatPayment($session["customer_details"]["email"], $session->metadata["orderId"]);
      $this->logger->notice("[Webhook] - Checkout validate : " . $session->client_reference_id);
    }
  }

  /**
   * Est appelée lors du traitement du paiement
   */
  abstract protected function treatPayment(string $email, string $orderId);


  /**
   * Est appelée lorsque l'utilisateur est entrain de payer
   */
  //abstract protected function treatPaying();

  /**
   * Génère l'événement
   */
  protected function generateEvent(?string $payload, ?string $sigHeader): Event
  {

    return \Stripe\Webhook::constructEvent(
      $payload,
      $sigHeader,
      $_ENV["STRIPE_WEBHOOK_ENDPOINT_SECRET"]
    );
  }

  /**
   * Gère les exceptions de type UnexpectedValue lors du trigger du webhook
   */
  protected function logUnexpectedValue(\UnexpectedValueException $e): void
  {
    // Invalid payload
    $this->logger->warning("[Webhook] - Webhook error while parsing basic request!");
    $this->logger->warning($e->getMessage(), ["Context" => "Invalid payload"]);
  }

  /**
   * Gère les exceptions de type SignatureVerificationException lors du trigger du webhook
   */
  protected function logSignatureException(?SignatureVerificationException $e): void
  {
    // Invalid signature
    $this->logger->warning("[Webhook] - Invalid signature!");
    $this->logger->warning($e->getMessage(), ["Context" => "Invalid signature"]);
  }

  /**
   * Lorsqu'un événement inconnu est complété
   */
  protected function logUnkownComplete(Event $event): void
  {
    $this->logger->notice("[Webhook] - Received unknown event type " . $event->type);
  }
}

