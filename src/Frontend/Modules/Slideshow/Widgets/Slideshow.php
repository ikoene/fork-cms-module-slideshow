<?php

namespace Frontend\Modules\Slideshow\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Modules\Slideshow\Engine\Model as FrontendSlideshowModel;

/**
 * This is a widget with a specific slideshow
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class Slideshow extends FrontendBaseWidget
{
    /**
     * Execute the extra
     *
     * @return void
     */
    public function execute()
    {
        // call the parent
        parent::execute();

        // load template
        $this->loadTemplate();

        // load the data
        $this->getData();

        // parse
        $this->parse();
    }

    /**
     * Load the data, don't forget to validate the incoming data
     *
     * @return void
     */
    private function getData()
    {
        // get image data
        $this->slides = FrontendSlideshowModel::getImages($this->data['gallery_id']);

            // only if it contains images
            $this->gallery = FrontendSlideshowModel::getGallery($this->data['gallery_id']);
    }

    /**
     * Parse
     *
     * @return void
     */
    private function parse()
    {
        // add CSS
        $this->header->addCSS(
            '/src/Frontend/Modules/' .
            $this->getModule() .
            '/Layout/Css/slideshow.css'
        );

        // assign
        $this->tpl->assign('widgetSlideshow', $this->slides);
        $this->tpl->assign('widgetGallery', $this->gallery);

        // get module settings
        $this->settings = FrontendModel::getModuleSettings('Slideshow');

        // should we use the settings per slide or the module settings
        if ($this->settings['settings_per_slide']==='true') {
                // load slideshow settings
                $this->tpl->assign(
                    'widgetSlideshowSettings',
                    FrontendSlideshowModel::getGallerySettings($this->data['gallery_id'])
                );
        } else {
            // load module settings
            $this->tpl->assign(
                'widgetSlideshowSettings',
                FrontendModel::getModuleSettings('Slideshow')
            );
        }
    }
}
