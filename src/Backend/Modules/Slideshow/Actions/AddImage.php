<?php

namespace Backend\Modules\Slideshow\Actions;

use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language as BL;
use Backend\Modules\Slideshow\Engine\Model as BackendSlideshowModel;

/**
 * This is the add-action, it will display a form to add a new image
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class AddImage extends BackendBaseActionAdd
{

    // the amount of image inputfields
    private $imageUploadFields = 5;

    /**
     * Execute the action
     *
     * @return  void
     */
    public function execute()
    {
        // get parameters
        $this->id = $this->getParameter('id');

        // does the item exists
        if ($this->id !== null && BackendSlideshowModel::existsGallery($this->id)) {
            // call parent, this will probably add some general CSS/JS or other required files
            parent::execute();

            // get all data for the item we want to edit
            $this->getData();

            // load the form
            $this->loadForm();

            // validate the form
            $this->validateForm();

            // parse
            $this->parse();

            // display the page
            $this->display();
        } else {
            // no item found, throw an exception, because somebody is fucking with our URL
             $this->redirect(BackendModel::createURLForAction('Index') . '&error=non-existing');
        }
    }


    /**
     * Get the data for a question
     *
     * @return  void
     */
    private function getData()
    {
        // get the record
        $this->record = BackendSlideshowModel::getGallery($this->id);
    }


    /**
     * Load the form
     *
     * @return  void
     */
    public function loadForm()
    {
        // create form
        $this->frm = new BackendForm('add');

        // add multiple image input fields
        for ($i = 0; $i< $this->imageUploadFields; $i++) {
            $this->ImageInput[]['formElements']['Image'] = $this->frm->addImage('image' . $i);
        }

    }

    /**
     * Parse the form
     *
     * @return  void
     */
    protected function parse()
    {
        // call parent
        parent::parse();

        // assign the active record and additional variables
        $this->tpl->assign('item', $this->record);

        // assign image input fields
        $this->tpl->assign('imageInput', $this->ImageInput);
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

            // validate all images
            for ($i = 0; $i < $this->imageUploadFields; $i++) {
                // validate fields
                if ($this->frm->getField('image'. $i)->isFilled()) {
                    // correct extension
                    if ($this->frm->getField('image'. $i)
                        ->isAllowedExtension(
                            array('jpg', 'jpeg', 'gif', 'png'),
                            BL::err('JPGGIFAndPNGOnly')
                        )
                    ) {
                        // correct mimetype?
                        $this->frm->getField('image'. $i)
                            ->isAllowedMimeType(
                                array('image/gif', 'image/jpg', 'image/jpeg', 'image/png'),
                                BL::err('JPGGIFAndPNGOnly')
                            );
                    }
                }
            }

            // no errors?
            if ($this->frm->isCorrect()) {
                // build item
                $item['language'] = $this->record['language'];
                $item['gallery_id'] = $this->id;

                for ($i = 0; $i < $this->imageUploadFields; $i++) {

                    if ($this->frm->getField('image'. $i)->isFilled()) {
                        // get the sequence everytime
                        $item['sequence'] = BackendSlideshowModel::getMaximumImageSequence($this->id) + 1;

                        // create new filename
                        $filename = time() . $i . "." . $this->frm->getField('image'. $i)->getExtension();

                        // add filename to item
                        $item['filename'] = $filename;

                        // If height is not set, scale the image proportionally to the given width
                        if ($this->record['height'] != 0) {
                            // upload image width gallery dimensions
                            $this->frm->getField('image'. $i)->createThumbnail(
                                FRONTEND_FILES_PATH .
                                '/slideshow/' .
                                $filename,
                                $this->record['width'],
                                $this->record['height'],
                                true,
                                false,
                                100
                            );
                        } else {
                            $this->frm->getField('image'. $i)->createThumbnail(
                                FRONTEND_FILES_PATH .
                                '/slideshow/' .
                                $filename,
                                $this->record['width'],
                                null,
                                true,
                                true,
                                100
                            );
                        }
                        // create thumbnail for later use
                        $this->frm->getField('image'. $i)->createThumbnail(
                            FRONTEND_FILES_PATH .
                            '/slideshow/thumbnails/' .
                            $filename,
                            100,
                            100,
                            false,
                            false,
                            100
                        );
                        // insert image values in database
                        BackendSlideshowModel::insertImage($item);
                    }
                }

                // trigger event
                BackendModel::triggerEvent($this->getModule(), 'after_edit', array('item' => $item));

                // everything is saved, so redirect to the overview
                $this->redirect(
                    BackendModel::createURLForAction('Edit') .
                    '&report=saved&var=' .
                    '&id=' .
                    $this->id .
                    '#images'
                );
            }
        }
    }
}
