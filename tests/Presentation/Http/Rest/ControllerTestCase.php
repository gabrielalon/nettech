<?php

namespace App\Tests\Presentation\Http\Rest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class ControllerTestCase extends WebTestCase
{
    /**
     * @param Response $response
     * @param int      $statusCode
     */
    protected function assertJsonResponse(Response $response, int $statusCode = 200): void
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    /**
     * @param Response $response
     * @param string   $property
     */
    protected function assertResponsePropertyExists(Response $response, string $property): void
    {
        $data = json_decode($response->getContent(), true);

        $this->assertTrue(isset($data[$property]));
    }
}
