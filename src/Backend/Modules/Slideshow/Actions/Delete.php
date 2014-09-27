<?php

namespace Backend\Modules\Slideshow\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language as BL;
use Backend\Modules\Slideshow\Engine\Model as BackendSlideshowModel;
use Backend\Modules\Search\Engine\Model as BackendSearchModel;

/**
 * This action will delete a slideshow
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class Delete extends BackendBaseActionDelete
{
    /**
     * Execute the action
     *
     * @return  void
     */
    public function execute()
    {
        // get parameters
        $this->id = $this->getParameter('id', 'int');

        // does the item exist
        if ($this->id !== null && BackendSlideshowModel::existsGallery($this->id)) {
            // call parent, this will probably add some general CSS/JS or other required files
            parent::execute();

            // delete widget
            BackendSlideshowModel::deleteWidget($this->id);

            // delete item
            BackendSlideshowModel::deleteGallery($this->id);

            // trigger event
            BackendModel::triggerEvent(
                $this->getModule(),
                'after_delete',
                array('id' => $this->id)
            );

            // item was deleted, so redirect
            $this->redirect(BackendModel::createURLForAction('Index') . '&report=deleted');
        } else {
            // something went wrong
            $this->redirect(BackendModel::createURLForAction('Index') . '&error=non-existing');
        }
    }
}
