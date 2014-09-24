<?php

namespace Frontend\Modules\Slideshow\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Slideshow\Engine\Model as FrontendSlideshowModel;

/**
 * This is the index-action
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class Index extends FrontendBaseBlock
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
        // get all galleries
        $this->items = FrontendSlideshowModel::getGalleries();

        // full url assets
        $this->full_url = FrontendNavigation::getURLForBlock('Slideshow', 'Detail');
        $this->full_url_category = FrontendNavigation::getURLForBlock('Slideshow', 'Category');
    }

    /**
     * Parse
     *
     * @return void
     */
    private function parse()
    {
        //assign
        $this->tpl->assign('full_url', $this->full_url);
        $this->tpl->assign('full_url_category', $this->full_url_category);
        $this->tpl->assign('galleries', $this->items);
    }
}
