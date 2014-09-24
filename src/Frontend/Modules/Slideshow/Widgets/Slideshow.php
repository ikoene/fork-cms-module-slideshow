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
        // get gallery data
        $this->gallery = FrontendSlideshowModel::getGallery($this->data['id']);

        // get image data
        $this->slides = FrontendSlideshowModel::getImages($this->data['id']);
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

        // add JS
        $this->addJS('Settings.js');

        // assign
        $this->tpl->assign('widgetSlideshow', $this->slides);
        $this->tpl->assign('widgetGallery', $this->gallery);

        if (!empty($this->gallery)) {
            // should we use the settings per slide or the module settings
            if (FrontendModel::getModuleSetting('Slideshow', 'settings_per_slide')) {
                $settings = FrontendSlideshowModel::getAllSettings($this->gallery['id']);
                $settings['id'] = $this->gallery['id'];
            } else {
                $settings = FrontendModel::getModuleSettings('Slideshow');
                $settings['id'] = $this->gallery['id'];
            }
            // pass settings to JS
            $this->addJSData(
                'slideshowSettings',
                $settings
            );
        }
    }
}
