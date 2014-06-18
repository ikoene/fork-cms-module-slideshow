<?php

namespace Frontend\Modules\Slideshow;

/**
 * This is the configuration-object
 *
 * @package     frontend
 * @subpackage  slideshow
 *
 * @author      Koen Vinken <koen@tagz.be>
 * @since       1.0
 */

use Frontend\Core\Engine\Base\Config as FrontendBaseConfig;


final class Config extends FrontendBaseConfig
{
    /**
     * The default action
     *
     * @var string
     */
    protected $defaultAction = 'index';


    /**
     * The disabled actions
     *
     * @var array
     */
    //protected $disabledActions = array();
}

?>