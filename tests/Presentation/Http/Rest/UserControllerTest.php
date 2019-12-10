<?php

namespace App\Tests\Presentation\Http\Rest;

class UserControllerTest extends ControllerTestCase
{
    public function testApiLoginCheck(): void
    {
        $client = static::createClient();
        $client->request(
            'POST', '/api/login_check', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => 'admin', 'password' => 'admin'])
        );

        $this->assertJsonResponse($client->getResponse(), 200);
        $this->assertResponsePropertyExists($client->getResponse(), 'token');

        $accessToken = json_decode($client->getResponse()->getContent(), true)['token'];

        $client->request(
            'GET', '/api/user/status', [], [],
            [
                'HTTP_AUTHORIZATION' => "Bearer {$accessToken}",
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['username' => 'admin', 'password' => 'admin'])
        );

        $this->assertJsonResponse($client->getResponse(), 200);
        $this->assertResponsePropertyExists($client->getResponse(), 'username');
    }
}
