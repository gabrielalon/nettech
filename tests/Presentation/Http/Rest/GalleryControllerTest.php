<?php

namespace App\Tests\Presentation\Http\Rest;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class GalleryControllerTest extends ControllerTestCase
{
    /**
     * @param KernelBrowser $client
     *
     * @return string
     */
    private function retrieveToken(KernelBrowser $client): string
    {
        $client->request(
            'POST', '/api/login_check', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => 'admin', 'password' => 'admin'])
        );

        return json_decode($client->getResponse()->getContent(), true)['token'];
    }

    public function testApiGalleriesRetrieve(): void
    {
        $client = static::createClient();
        $accessToken = $this->retrieveToken($client);

        $client->request(
            'GET', '/api/gallery/list/watch-the-deer', [], [],
            [
                'HTTP_AUTHORIZATION' => "Bearer {$accessToken}",
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['username' => 'admin', 'password' => 'admin'])
        );

        $this->assertJsonResponse($client->getResponse(), 200);
        $this->assertResponsePropertyExists($client->getResponse(), 'galleries');
        $this->assertResponsePropertyExists($client->getResponse(), 'searcher');
    }
}
