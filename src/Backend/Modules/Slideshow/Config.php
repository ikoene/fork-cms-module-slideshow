<?php

namespace Backend\Modules\Slideshow;

/**
 * This is the configuration-object for the slideshow module
 *
 * @package     backend
 * @subpackage  slideshow
 *
 * @author      Koen Vinken <koen@tagz.be>
 * @since       1.0
 */

use Backend\Core\Engine\Base\Config as BackendBaseConfig;

Class Config extends BackendBaseConfig
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
    protected $disabledActions = array();


    /**
     * The disabled AJAX-actions
     *
     * @var array
     */
    protected $disabledAJAXActions = array();
}

?>