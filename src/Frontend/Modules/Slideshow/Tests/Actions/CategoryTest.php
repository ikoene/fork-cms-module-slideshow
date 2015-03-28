<?php

namespace Frontend\Modules\Slideshow\Actions;

use Common\WebTestCase;

class CategoryTest extends WebTestCase
{
    public function testCategoryHasPage()
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

        $crawler = $client->request('GET', '/en/slideshow/category/slideshowcategory-for-tests');
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        $this->assertStringStartsWith(
            'SlideshowCategory for tests',
            $crawler->filter('title')->text()
        );
    }

    public function testNonExistingCategoryGalleryGives404()
    {
        $client = static::createClient();

        $client->request('GET', '/en/slideshow/category/non-existing');
        $this->assertIs404($client);
    }

    public function testCategoryPageContainsSlideshowCategory()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/en/slideshow/category/slideshowcategory-for-tests');

        $this->assertContains('Slideshow for functional tests', $client->getResponse()->getContent());
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

}
