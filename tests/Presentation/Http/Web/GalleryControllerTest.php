<?php

namespace App\Tests\Presentation\Http\Web;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GalleryControllerTest extends WebTestCase
{
    public function testVisitSourcesPage(): void
    {
        $client = static::createClient();

        $client->request('GET', '/gallery/source');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSelectorTextContains('html body div div div h3', 'Gallery sources');
        self::assertSelectorTextContains('html body div div div table tbody tr td', 'watch-the-deer');
    }

    public function testVisitGalleriesPage(): void
    {
        $client = static::createClient();

        $client->request('GET', '/gallery/list/watch-the-deer');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSelectorTextContains('html body div div div h3', 'Gallery list');
    }
}
