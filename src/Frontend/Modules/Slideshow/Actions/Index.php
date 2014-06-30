<?php

namespace Frontend\Modules\Slideshow\Actions;

/**
 * This is the configuration-object
 *
 * @package     frontend
 * @subpackage  slideshow
 *
 * @author      Koen Vinken <koen@tagz.be>
 * @since       1.0
 */

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Slideshow\Engine\Model as FrontendSlideshowModel;

class Index extends FrontendBaseBlock
{
    /**
     * Execute the extra
     *
     * @return  void
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
        //get all galleries
        $this->item = FrontendSlideshowModel::getGalleries();
        
        //full url asset        
        $this->full_url = FrontendNavigation::getURLForBlock('Slideshow', 'detail');
        $this->full_url_category = FrontendNavigation::getURLForBlock('Slideshow', 'category');
    }

    /**
     * Parse
     *
     * @return  void
     */
    private function parse()
    {   
        //assign
        $this->tpl->assign('full_url', $this->full_url);
        $this->tpl->assign('full_url_category', $this->full_url_category);
        $this->tpl->assign('galleries', $this->item);
    }
}

?>
