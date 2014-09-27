<?php

namespace Frontend\Modules\Slideshow\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Slideshow\Engine\Model as FrontendSlideshowModel;

/**
 * This is the detail-action
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class Detail extends FrontendBaseBlock
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
     * Get the data
     *
     * @return void
     */
    private function getData()
    {
        // check for errors
        if ($this->URL->getParameter(1) === null) {
            $this->redirect(FrontendNavigation::getURL(404));
        }

        // get the gallery
        $this->record = FrontendSlideshowModel::getGalleryByURL($this->URL->getParameter(1));

        if (empty($this->record)) {
            $this->redirect(FrontendNavigation::getURL(404));
        }

        // get image data
        $this->slides = FrontendSlideshowModel::getImages($this->record['gallery_id']);

        // get gallery data
        $this->gallery = FrontendSlideshowModel::getGallery($this->record['gallery_id']);
    }

    /**
     * Parse
     *
     * @return void
     */
    private function parse()
    {
        // add CSS and JS
        $this->header->addCSS(
            '/src/Frontend/Modules/' .
            $this->getModule() .
            '/Layout/Css/slideshow.css'
        );

        // add JS
        $this->addJS('Settings.js');

        // set meta
        $this->header->setPageTitle(
            $this->record['meta_title'],
            ($this->record['title_overwrite'] == 'Y')
        );
        $this->header->addMetaDescription(
            $this->record['meta_description'],
            ($this->record['description_overwrite'] == 'Y')
        );
        $this->header->addMetaKeywords(
            $this->record['meta_keywords'],
            ($this->record['keywords_overwrite'] == 'Y')
        );

        // assign
        $this->tpl->assign('slideshow', $this->slides);
        $this->tpl->assign('gallery', $this->gallery);

        // assign navigation
        $this->tpl->assign(
            'navigation',
            FrontendSlideshowModel::getNavigation($this->record['gallery_id'])
        );

        // should we use the settings per slide or the module settings
        if (FrontendModel::getModuleSetting('Slideshow', 'settings_per_slide')) {
            $settings = FrontendSlideshowModel::getAllSettings($this->record['gallery_id']);
            $settings['id'] = $this->record['gallery_id'];
        } else {
            $settings = FrontendModel::getModuleSettings('Slideshow');
            $settings['id'] = $this->record['gallery_id'];
        }

        // pass settings to JS
        $this->addJSData(
            'slideshowSettings',
            $settings
        );

    }
}
