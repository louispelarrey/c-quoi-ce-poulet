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

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJson($response->getContent());
    }

    public function testGetUser(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET, 
            '/api/users/1'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJson($response->getContent());
    }
}
