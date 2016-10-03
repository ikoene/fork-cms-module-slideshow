<?php

namespace Frontend\Modules\Slideshow\Tests\Actions;

use Common\WebTestCase;

class IndexTest extends WebTestCase
{
    public function testIndexContainsSlideshowGalleries()
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

        $client->request('GET', '/en/slideshow');
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        $this->assertContains(
            'Slideshow for functional tests',
            $client->getResponse()->getContent()
        );
    }
}
