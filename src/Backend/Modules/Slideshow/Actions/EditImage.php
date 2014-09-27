<?php

namespace Backend\Modules\Slideshow\Actions;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\File\File;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Meta as BackendMeta;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\DataGridDB as BackendDataGridDB;
use Backend\Core\Engine\DataGridFunctions as BackendDataGridFunctions;
use Backend\Modules\Slideshow\Engine\Model as BackendSlideshowModel;
use Backend\Modules\Search\Engine\Model as BackendSearchModel;
use Backend\Modules\Tags\Engine\Model as BackendTagsModel;
use Backend\Modules\Users\Engine\Model as BackendUsersModel;

/**
 * This is the edit-action, it will display a form to edit an existing image.
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class EditImage extends BackendBaseActionEdit
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
        $this->galleryId = $this->getParameter('galleryid', 'int');

        // does the item exists
        if ($this->id !== null && BackendSlideshowModel::existsImage($this->id)) {
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
            $this->redirect(
                BackendModel::createURLForAction('Index') .
                '&error=non-existing'
            );
        }
    }


    /**
     * Get the data for a question
     *
     * @return  void
     */
    private function getData()
    {
        // get the gallery record
        $this->record = BackendSlideshowModel::getImage($this->id);
        $this->gallery = BackendSlideshowModel::getGallery($this->galleryId);

        $this->record['data'] = unserialize($this->record['data']);
        $this->record['link'] = $this->record['data']['link'];
    }

    /**
     * Load the form
     *
     * @return  void
     */
    private function loadForm()
    {
        // create form
        $this->frm = new BackendForm('edit');

        $internalLinks = BackendSlideshowModel::getInternalLinks();

        $internalLink = ($this->record['link']['type'] == 'internal') ? $this->record['link']['id'] : '';
        $externalLink = ($this->record['link']['type'] == 'external') ? $this->record['link']['url'] : '';

        // set hidden values
        $rbtHiddenValues[] = array('label' => BL::lbl('Hidden'), 'value' => 'Y');
        $rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');

        // create elements
        $this->frm->addText('title', $this->record['title']);
        $this->frm->addImage('filename');
        $this->frm->addEditor('caption', $this->record['caption']);
        $this->frm->addRadiobutton('hidden', $rbtHiddenValues, $this->record['hidden']);
        $this->frm->addCheckbox('external_link', ($this->record['link']['type'] == 'external'));
        $this->frm->addText('external_url', $externalLink);
        $this->frm->addDropdown('internal_url', $internalLinks, $internalLink,
            false,
            'chzn-select'
        )->setDefaultElement('');
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
        $this->tpl->assign('gallery', $this->gallery);
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
            if ($this->frm->getField('filename')->isFilled()) {
                // correct extension?
                if ($this->frm->getField('filename')
                    ->isAllowedExtension(
                        array('jpg', 'jpeg', 'gif', 'png'),
                        BL::err('JPGGIFAndPNGOnly')
                    )
                ) {
                    // correct mimetype?
                    $this->frm->getField('filename')
                        ->isAllowedMimeType(
                            array('image/gif', 'image/jpg', 'image/jpeg', 'image/png'),
                            BL::err('JPGGIFAndPNGOnly')
                        );
                }
            }

            // no errors?
            if ($this->frm->isCorrect()) {
                // build item
                $item['id'] = $this->id;
                $item['language'] = $this->record['language'];
                $item['title'] = $this->frm->getField('title')->getValue();
                $item['caption'] = $this->frm->getField('caption')->getValue(true);
                $item['hidden'] = $this->frm->getField('hidden')->getValue();

                //get module settings
                $dimensions = BackendModel::getModuleSettings('slideshow');

                // the extra data
                $data = array('link' => null);

                // links
                if($this->frm->getField('internal_url')->isFilled())
                {
                    $data['link'] = array(
                        'type' => 'internal',
                        'id' => $this->frm->getField('internal_url')->getValue()
                    );
                }

                // external links
                if($this->frm->getField('external_link')->getChecked())
                {
                    $data['link'] = array(
                        'type' => 'external',
                        'url' => $this->frm->getField('external_url')->getValue()
                    );
                }

                $item['data'] = serialize($data);

                if ($this->frm->getField('filename')->isFilled()) {
                    // only delete the picture when there is one allready
                    if (!empty($this->record['filename'])) {
                        $fs = new Filesystem();

                        $fs->remove(
                            FRONTEND_FILES_PATH .
                            '/slideshow/thumbnails/' .
                            $this->record['filename']
                        );
                        $fs->remove(
                            FRONTEND_FILES_PATH .
                            '/slideshow/' .
                            $this->record['filename']
                        );
                    }

                    // create new filename
                    $filename = time() . "." . $this->frm->getField('filename')->getExtension();

                    // add filename to item
                    $item['filename'] = $filename;

                    // If height is not set, scale the image proportionally to the given width
                    if ($this->gallery['height'] != 0) {
                        // upload image width gallery dimensions
                        $this->frm->getField('filename')->createThumbnail(
                            FRONTEND_FILES_PATH . '/slideshow/' . $filename,
                            $this->gallery['width'],
                            $this->gallery['height'],
                            true,
                            false,
                            100
                        );
                    } else {
                        $this->frm->getField('filename')->createThumbnail(
                            FRONTEND_FILES_PATH . '/slideshow/' . $filename,
                            $this->gallery['width'],
                            null,
                            true,
                            true,
                            100
                        );
                    }

                    // create thumbnail
                    $this->frm->getField('filename')->createThumbnail(
                        FRONTEND_FILES_PATH . '/slideshow/thumbnails/' . $filename,
                        100,
                        100,
                        false,
                        false,
                        100
                    );
                }

                // update image values in database
                BackendSlideshowModel::updateImage($item);

                // trigger event
                BackendModel::triggerEvent(
                    $this->getModule(),
                    'after_edit',
                    array('item' => $item)
                );

                // everything is saved, so redirect to the overview
                $this->redirect(
                    BackendModel::createURLForAction('Edit') .
                    '&report=Saved&id=' . $this->galleryId .
                    '&highlight=' . $this->id .
                    '#images'
                );
            }
        }
    }
}
