<?php

namespace Frontend\Modules\Slideshow\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Slideshow\Engine\Model as FrontendSlideshowModel;

/**
 * This is the category-action
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class Category extends FrontendBaseBlock
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

        // get the gallery meta based on the url
        $this->record = FrontendSlideshowModel::getGalleriesByURL($this->URL->getParameter(1));

        // full url asset
        $this->full_url = FrontendNavigation::getURLForBlock('Slideshow', 'detail');
        $this->full_url_category = FrontendNavigation::getURLForBlock('Slideshow', 'category');
    }

    /**
     * Parse
     *
     * @return void
     */
    private function parse()
    {
        // assign
        $this->tpl->assign('full_url', $this->full_url);
        $this->tpl->assign('full_url_category', $this->full_url_category);
        $this->tpl->assign('galleries', $this->record);

        // set meta
        $this->header->setPageTitle(
            $this->record[0]['category_meta_title'],
            ($this->record[0]['category_title_overwrite'] == 'Y')
        );
        $this->header->addMetaDescription(
            $this->record[0]['category_meta_description'],
            ($this->record[0]['category_description_overwrite'] == 'Y')
        );
        $this->header->addMetaKeywords(
            $this->record[0]['category_meta_keywords'],
            ($this->record[0]['category_keywords_overwrite'] == 'Y')
        );
    }
}
