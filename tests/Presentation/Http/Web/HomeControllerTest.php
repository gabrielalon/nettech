<?php

namespace App\Tests\Presentation\Http\Web;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testVisitHomePage(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSelectorTextContains('html body div div div h1', 'BoostTypers');
    }
}
