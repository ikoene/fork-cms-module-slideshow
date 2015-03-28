<?php

namespace Backend\Modules\Slideshow\DataFixtures;

class LoadSlideshowImages
{
    public function load(\SpoonDatabase $database)
    {
        $galleryId = $database->getVar(
            'SELECT id
             FROM slideshow_galleries
             WHERE title = :title AND language = :language
             LIMIT 1',
            array(
                'title' => 'Slideshow for functional tests',
                'language' => 'en',
            )
        );

        $database->insert(
            'slideshow_images',
            array(
                'id' => 1,
                'gallery_id' => $galleryId,
                'language' => 'en',
                'title' => 'Slideshow image for functional tests',
                'caption' => '<p>caption of the slideshow image</p>',
                'filename' => 'slideshow-image-for-functional-tests'
            )
        );
    }
}
