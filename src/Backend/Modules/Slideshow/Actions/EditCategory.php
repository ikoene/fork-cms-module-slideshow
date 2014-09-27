<?php

namespace Backend\Modules\Slideshow\Actions;

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Meta as BackendMeta;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Slideshow\Engine\Model as BackendSlideshowModel;
use Backend\Modules\Search\Engine\Model as BackendSearchModel;
use Backend\Modules\Tags\Engine\Model as BackendTagsModel;

/**
 * This is the edit-action, it will display a form to edit an existing category.
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class EditCategory extends BackendBaseActionEdit
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

        // does the item exists?
        if ($this->id !== null && BackendSlideshowModel::existsCategory($this->id)) {
            // call parent, this will probably add some general CSS/JS or other required files
            parent::execute();

            // get all data for the item we want to edit
            $this->getData();

            // load the form
            $this->loadForm();

            // validate the form
            $this->validateForm();

            // parse the form
            $this->parse();

            // display the page
            $this->display();
        } else {
            // no item found, throw an exceptions, because somebody is fucking with our URL
            $this->redirect(
                BackendModel::createURLForAction('Categories') .
                '&error=non-existing'
            );
        }
    }


    /**
     * Get the data
     *
     * @return  void
     */
    private function getData()
    {
        $this->record = BackendSlideshowModel::getCategory($this->id);
    }

    /**
     * Load the form
     *
     * @return  void
     */
    private function loadForm()
    {
        // create form
        $this->frm = new BackendForm('edit_category');

        // create elements
        $this->frm->addText('title', $this->record['title'], null, 'inputText title', 'inputTextError title');

        // meta object
        $this->meta = new BackendMeta($this->frm, $this->record['meta_id'], 'title', true);

        // set callback for generating a unique URL
        $this->meta->setURLCallback(
            'Backend\Modules\Slideshow\Engine\Model',
            'getURLForCategory',
            array($this->record['id'])
        );
    }


    /**
     * Parse the form
     *
     * @return  void
     */
    protected function parse()
    {
        parent::parse();

        $this->tpl->assign('id', $this->record['id']);
        $this->tpl->assign('title', $this->record['title']);

        // delete allowed?
        $this->tpl->assign(
            'showSlideshowDeleteCategory',
            BackendSlideshowModel::deleteCategoryAllowed($this->id)
        );
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

            $this->meta->validate();

            // no errors?
            if ($this->frm->isCorrect()) {
                // build item
                $item['id'] = $this->id;
                $item['meta_id'] = $this->meta->save(true);
                $item['title'] = $this->frm->getField('title')->getValue();
                $item['language'] = BL::getWorkingLanguage();

                // update the item
                BackendSlideshowModel::updateCategory($item);

                // trigger event
                BackendModel::triggerEvent(
                    $this->getModule(),
                    'after_edite_category',
                    array('item' => $item)
                );

                // everything is saved, so redirect to the overview
                $this->redirect(
                    BackendModel::createURLForAction('Categories') .
                    '&report=edited-category&var=' .
                    urlencode($item['title']) .
                    '&highlight=' .
                    $item['id']
                );
            }
        }
    }
}
