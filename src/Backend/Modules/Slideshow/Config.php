<?php

namespace Backend\Modules\Slideshow;

use Backend\Core\Engine\Base\Config as BackendBaseConfig;

/**
 * This is the configuration-object for the slideshow module
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class Config extends BackendBaseConfig
{
    /**
     * The default action
     *
     * @var string
     */
    protected $defaultAction = 'Index';


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
