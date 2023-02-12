<?php

declare(strict_types=1);

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEndPoint extends WebTestCase
{
    private array $serverInformations = [
        'CONTENT_TYPE' => 'application/json',
        'ACCEPT' => 'application/json',
    ];

    public function getResponseFromRequest(string $method, string $uri, string $payload =''): Response
    {
        $client = static::createClient();

        $client->request(
            $method,
            $uri,
            [],
            [], 
            $this->serverInformations, 
            $payload
        );

        return $client->getResponse();
    }
}