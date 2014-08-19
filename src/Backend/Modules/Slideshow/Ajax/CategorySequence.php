<?php

namespace Backend\Modules\Slideshow\Ajax;

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAJAXAction;
use Backend\Modules\Slideshow\Engine\Model as BackendSlideshowModel;

/**
 * Reorder categories
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class CategorySequence extends BackendBaseAJAXAction
{
    /**
     * Execute the action
     *
     * @return  void
     */
    public function execute()
    {
        // call parent, this will probably add some general CSS/JS or other required files
        parent::execute();

        // get parameters
        $newIdSequence = trim(\SpoonFilter::getPostValue('new_id_sequence', null, '', 'string'));

        // list id
        $ids = (array) explode(',', rtrim($newIdSequence, ','));

        // loop id's and set new sequence
        foreach ($ids as $i => $id) {
            // build item
            $item['id'] = (int) $id;

            // change sequence
            $item['sequence'] = $i + 1;

            // update sequence
            BackendSlideshowModel::updateCategorySequence($item);
        }

        // success output
        $this->output(self::OK, null, 'sequence updated');
    }
}
