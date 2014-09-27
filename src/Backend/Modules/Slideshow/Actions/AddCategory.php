<?php

namespace Backend\Modules\Slideshow\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Meta as BackendMeta;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Slideshow\Engine\Model as BackendSlideshowModel;

/**
 * This is the add-action, it will display a form to create a new category
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class AddCategory extends BackendBaseActionAdd
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

        // load the form
        $this->loadForm();

        // validate the form
        $this->validateForm();

        // parse
        $this->parse();

        // display the page
        $this->display();
    }


    /**
     * Load the form
     *
     * @return  void
     */
    private function loadForm()
    {
        // create form
        $this->frm = new BackendForm('add_category');

        // create elements
        $this->frm->addText('title', null, 255, 'inputText title', 'inputTextError title');

        // meta object
        $this->meta = new BackendMeta($this->frm, null, 'title', true);

        // set callback for generating a unique URL
        $this->meta->setURLCallback('Backend\Modules\Slideshow\Engine\Model', 'getURLForCategory');
    }


    /**
     * Validate the form
     *
     * @return  void
     */
    private function validateForm()
    {
        // is the form submitted?
        if ($this->frm->isSubmitted()) {
            // cleanup the submitted fields, ignore fields that were added by hackers
            $this->frm->cleanupFields();

            // validate fields
            $this->frm->getField('title')->isFilled(BL::err('TitleIsRequired'));

            // validate meta
            $this->meta->validate();

            // no errors?
            if ($this->frm->isCorrect()) {
                // build item
                $item['title'] = $this->frm->getField('title')->getValue();
                $item['language'] = BL::getWorkingLanguage();
                $item['meta_id'] = $this->meta->save();
                $item['sequence'] = BackendSlideshowModel::getMaximumCategorySequence() + 1;

                // insert the item
                $item['id'] = BackendSlideshowModel::insertCategory($item);

                // trigger event
                BackendModel::triggerEvent($this->getModule(), 'after_add_category', array('item' => $item));

                // everything is saved, so redirect to the overview
                $this->redirect(
                    BackendModel::createURLForAction('Categories') .
                    '&report=added-category&var=' .
                    urlencode($item['title']) .
                    '&highlight=' .
                    $item['id']
                );
            }
        }
    }
}
