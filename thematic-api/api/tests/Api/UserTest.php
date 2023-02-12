<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserTest extends ApiTestCase
{
    public function testLoginUser(): void
    {
        $response = static::createClient()->request('POST', '/api/auth', ['json' => [
            'email' => 'louispelarrey@gmail.com',
            'password' => 'string',
        ]]);

        $GLOBALS['token'] = $response->toArray()['token'];
        $this->assertResponseIsSuccessful();
    }

    public function testGetAllRestaurants() : void
    {
        $response = static::createClient()->request('GET', '/api/restaurants', ['auth_bearer' => $GLOBALS['token']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/contexts/Restaurant',
            '@id' => '/api/restaurants',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 1,
        ]);
    }

    public function testGetRestaurant() : void
    {
        $response = static::createClient()->request('GET', '/api/restaurants/1', ['auth_bearer' => $GLOBALS['token']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/contexts/Restaurant',
            '@id' => '/api/restaurants/1',
            '@type' => 'Restaurant',
            'id' => 1,
        ]);
    }

    public function testGetMeals() : void
    {
        $response = static::createClient()->request('GET', '/api/meals', ['auth_bearer' => $GLOBALS['token']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/contexts/Meals',
            '@id' => '/api/meals',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 2,
        ]);
    }

    public function testGetMeal() : void
    {
        $response = static::createClient()->request('GET', '/api/meals/1', ['auth_bearer' => $GLOBALS['token']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/contexts/Meals',
            '@id' => '/api/meals/1',
            '@type' => 'Meals',
            'id' => 1,
        ]);
    }


}
