<?php

namespace Backend\Modules\Slideshow\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Slideshow\Engine\Model as BackendSlideshowModel;

/**
 * This action will delete a category
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class DeleteCategory extends BackendBaseActionDelete
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
        if ($this->id !== null && BackendSlideshowModel::existsCategory($this->id)) {
            // call parent, this will probably add some general CSS/JS or other required files
            parent::execute();

            // get item
            $this->record = BackendSlideshowModel::getCategory($this->id);

            // delete item
            BackendSlideshowModel::deleteCategory($this->id);

            // trigger event
            BackendModel::triggerEvent($this->getModule(), 'after_delete_category', array('id' => $this->id));

            // item was deleted, so redirect
            $this->redirect(
                BackendModel::createURLForAction('Categories') .
                '&report=deleted&var=' .
                urlencode($this->record['title'])
            );
        } else {
            // something went wrong
            $this->redirect(BackendModel::createURLForAction('Categories') . '&error=non-existing');
        }
    }
}
