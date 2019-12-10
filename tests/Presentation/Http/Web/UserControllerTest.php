<?php

namespace App\Tests\Presentation\Http\Web;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testVisitSourcesPage(): void
    {
        $client = static::createClient();

        $client->request('GET', '/user/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSelectorTextContains('html body div div div h3', 'Users');
    }
}
