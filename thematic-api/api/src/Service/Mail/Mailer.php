<?php

namespace App\Service\Mail;

use Psr\Log\LoggerInterface;
use SendGrid\Mail\Mail;
use SendGrid;

class Mailer
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function sendMail(string $to, string $subject, string $body): bool
    {
        $email = new Mail();
        $email->setFrom("noreply@cquoicepoulet.com", "C quoi ce poulet");
        $email->setSubject($subject);
        $email->addTo($to);
        $email->addContent("text/html", trim($body));
        $sendgrid = new SendGrid($_ENV['SENDGRID_API_KEY']);
        try {
            $sendgrid->send($email);
            return true;
        } catch (\Exception $e) {
            $this->logger->warning('Caught exception with response from sendGrid: ' . print_r($sendgrid, true) . ". Error: " . $e->getMessage() . "\n");
            dd($e->getMessage());
            return false;
        }
    }
}
