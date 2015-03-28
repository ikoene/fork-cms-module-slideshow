<?php

namespace Frontend\Modules\Slideshow\Actions;

use Common\WebTestCase;

class DetailTest extends WebTestCase
{
    public function testSlideshowGalleryHasDetailPage()
    {
        $client = static::createClient();
        $this->loadFixtures(
            $client,
            array(
                'Backend\Modules\Slideshow\DataFixtures\LoadSlideshowCategories',
                'Backend\Modules\Slideshow\DataFixtures\LoadSlideshowGalleries',
                'Backend\Modules\Slideshow\DataFixtures\LoadSlideshowImages',
            )
        );

        $crawler = $client->request('GET', '/en/slideshow');
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        $link = $crawler->selectLink('Slideshow for functional tests')->link();
        $crawler = $client->click($link);

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        $this->assertStringEndsWith(
            '/en/slideshow/detail/slideshow-for-functional-tests',
            $client->getHistory()->current()->getUri()
        );
        $this->assertStringStartsWith(
            'Slideshow for functional tests',
            $crawler->filter('title')->text()
        );
    }

    public function testNonExistingSlideshowGalleryGives404()
    {
        $client = static::createClient();

        $client->request('GET', '/en/slideshow/detail/non-existing');
        $this->assertIs404($client);
    }
}
