<?php

namespace Backend\Modules\Slideshow\DataFixtures;

class LoadSlideshowGalleries
{
    public function load(\SpoonDatabase $database)
    {
        $metaId = $database->insert(
            'meta',
            array(
                'keywords' => 'Slideshow for functional tests',
                'description' => 'Slideshow for functional tests',
                'title' => 'Slideshow for functional tests',
                'url' => 'slideshow-for-functional-tests',
            )
        );

        $categoryId = $database->getVar(
            'SELECT id
             FROM slideshow_categories
             WHERE title = :title AND language = :language
             LIMIT 1',
            array(
                'title' => 'SlideshowCategory for tests',
                'language' => 'en',
            )
        );

        $database->insert(
            'slideshow_galleries',
            array(
                'id' => 1,
                'meta_id' => $metaId,
                'category_id' => $categoryId,
                'user_id' => 1,
                'language' => 'en',
                'title' => 'Slideshow for functional tests',
                'description' => '<p>Description of the slideshow</p>',
                'width' => 200,
                'height' => 200,
                'publish_on' => '2015-03-27 00:00:00',
                'created_on' => '2015-03-27 00:00:00',
                'edited_on' => '2015-03-27 00:00:00',
            )
        );

        $database->insert(
            'search_index',
            array(
                'module' => 'Slideshow',
                'other_id' => 1,
                'field' => 'title',
                'value' => 'Slideshow for functional tests',
                'language' => 'en',
                'active' => 'Y',
            )
        );
    }
}
