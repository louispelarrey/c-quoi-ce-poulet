<?php

declare(strict_types=1);

namespace App\Tests\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTest extends AbstractEndPoint
{
    
    public function testLogin(): void
    {
        $payload = '{"email": "louispelarrey@gmail.com", "password": "string"}';
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST, 
            '/api/auth',
        $payload);
        
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded->token);
        
    }
}
