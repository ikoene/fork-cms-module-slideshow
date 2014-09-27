<?php

namespace Backend\Modules\Slideshow\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Slideshow\Engine\Model as BackendSlideshowModel;

/**
 * This action will delete an image
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class DeleteImage extends BackendBaseActionDelete
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
        $this->gallery_id = $this->getParameter('gallery_id', 'int');

        // does the item exist
        if ($this->id !== null && BackendSlideshowModel::existsImage($this->id)) {
            // call parent, this will probably add some general CSS/JS or other required files
            parent::execute();

            // delete item
            BackendSlideshowModel::deleteImage($this->id);

            // trigger event
            BackendModel::triggerEvent($this->getModule(), 'after_delete_image', array('id' => $this->id));

            // item was deleted, so redirect
            $this->redirect(
                BackendModel::createURLForAction('Edit') .
                '&report=deleted&var=' .
                '&id=' .
                $this->gallery_id .
                '#images'
            );
        } else {
            // something went wrong
            $this->redirect(BackendModel::createURLForAction('Index') . '&error=non-existing');
        }
    }
}
