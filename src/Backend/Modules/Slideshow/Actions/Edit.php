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
 * This is the edit-action, it will display a form to edit an existing item
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class Edit extends BackendBaseActionEdit
{
    /**
     * The available categories
     *
     * @var array
     */
    private $categories;


    /**
     * Execute the action
     *
     * @return  void
     */
    public function execute()
    {
        // get parameters
        $this->id = $this->getParameter('id', 'int');

        // does the item exists
        if ($this->id !== null && BackendSlideshowModel::existsGallery($this->id)) {
            // call parent, this will probably add some general CSS/JS or other required files
            parent::execute();

            // get all data for the item we want to edit
            $this->getData();

            // load the form
            $this->loadForm();

            // load datagrids
            $this->loadDataGrid();

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
        $this->settings = BackendSlideshowModel::getAllSettings($this->id);

        // get categories
        $this->categories = BackendSlideshowModel::getCategoriesForDropdown();
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

        // set hidden values
        $rbtHiddenValues[] = array('label' => BL::lbl('Hidden'), 'value' => 'Y');
        $rbtHiddenValues[] = array('label' => BL::lbl('Published'), 'value' => 'N');

        // create elements
        $this->frm->addText('title', $this->record['title'])->setAttribute('id', 'title');
        $this->frm->getField('title')->setAttribute(
            'class',
            'title ' . $this->frm->getField('title')->getAttribute('class')
        );
        $this->frm->addImage('filename');
        $this->frm->addCheckbox('delete_image');
        $this->frm->addText('width', $this->record['width']);
        $this->frm->addText('height', $this->record['height']);
        $this->frm->addEditor('description', $this->record['description']);
        $this->frm->addDropdown('categories', $this->categories, $this->record['category_id']);
        $this->frm->addRadiobutton('hidden', $rbtHiddenValues, $this->record['hidden']);
        $this->frm->addDate('publish_on_date', $this->record['publish_on']);
        $this->frm->addTime('publish_on_time', date('H:i', $this->record['publish_on']));
        $this->frm->addCheckbox('id');

        // create settingsform elements
        $this->frm->addDropdown(
            'animation',
            array(
                'slide' => BL::lbl('SlideshowSlide', $this->getModule()),
                'fade' => BL::lbl('SlideshowFade', $this->getModule())),
                $this->settings['animation']
            );
        $this->frm->addDropdown(
            'direction',
            array(
                'horizontal' => BL::lbl('SlideshowHorizontal', $this->getModule()),
                'vertical' => BL::lbl('SlideshowVertical', $this->getModule())),
                $this->settings['direction']
            );
        $this->frm->addDropdown(
            'slideshow_speed',
            array_combine(range(1, 30), range(1, 30)),
            $this->settings['slideshow_speed']
        );
        $this->frm->addDropdown(
            'animation_speed',
            array_combine(range(1, 5), range(1, 5)),
            $this->settings['animation_speed']
        );
        $this->frm->addCheckbox(
            'direction_navigation',
            ($this->settings['direction_navigation'] === 'true' ? true : false)
        );
        $this->frm->addCheckbox(
            'control_navigation',
            ($this->settings['control_navigation'] === 'true' ? true : false)
        );
        $this->frm->addCheckbox(
            'thumbnail_navigation',
            ($this->settings['thumbnail_navigation'] === 'true' ? true : false)
        );
        $this->frm->addCheckbox(
            'keyboard',
            ($this->settings['keyboard'] === 'true' ? true : false)
        );
        $this->frm->addCheckbox(
            'mousewheel',
            ($this->settings['mousewheel'] === 'true' ? true : false)
        );
        $this->frm->addCheckbox(
            'touch',
            ($this->settings['touch'] === 'true' ? true : false)
        );
        $this->frm->addCheckbox(
            'randomize',
            ($this->settings['randomize'] === 'true' ? true : false)
        );
        $this->frm->addCheckbox(
            'auto_animate',
            ($this->settings['auto_animate'] === 'true' ? true : false)
        );
        $this->frm->addCheckbox(
            'animation_loop',
            ($this->settings['animation_loop'] === 'true' ? true : false)
        );

        // meta object
        $this->meta = new BackendMeta($this->frm, $this->record['meta_id'], 'title', true);

        // set callback for generating a unique URL
        $this->meta->setURLCallback(
            'Backend\Modules\Slideshow\Engine\Model',
            'getURLForGallery',
            array($this->record['id'])
        );

    }

    /**
     * Loads the datagrid
     *
     * @return  void
     */

    private function loadDataGrid()
    {
        // create datagrid
        $this->dataGrid = new BackendDataGridDB(
            BackendSlideshowModel::QRY_DATAGRID_BROWSE_IMAGES,
            array($this->id)
        );

        // add mass checkboxes
        $this->dataGrid->setMassActionCheckboxes('checkbox', '[id]');

        // disable paging
        $this->dataGrid->setPaging(false);

        // create a thumbnail preview
        $this->dataGrid->addColumn('preview', 'Preview');
        $this->dataGrid->setColumnFunction(
            array('Backend\Modules\Slideshow\Engine\Model', 'getPreview'),
            '[filename]',
            'preview',
            true
        );

        // enable drag and drop
        $this->dataGrid->enableSequenceByDragAndDrop();

        // our JS needs to know an id, so we can send the new order
        $this->dataGrid->setRowAttributes(array('id' => '[id]'));
        $this->dataGrid->setAttributes(array('data-action' => "ImageSequence"));

        // hide
        $this->dataGrid->setColumnHidden('filename');

        // add edit column
        $this->dataGrid->addColumn(
            'edit',
            null,
            BL::lbl('Edit'),
            BackendModel::createURLForAction('EditImage') .'&amp;id=[id]&amp;galleryid='. $this->id,
            BL::lbl('Edit')
        );

        // set column order
        $this->dataGrid->setColumnsSequence(
            'dragAndDropHandle',
            'checkbox',
            'preview',
            'hidden',
            'caption',
            'edit'
        );

        // add mass action dropdown
        $ddmMassAction = new \SpoonFormDropdown(
            'action',
            array('delete' => BL::getLabel('Delete'),
                'hide' => BL::getLabel('Hide'),
                'publish' => BL::getLabel('Publish')
            ),
            'delete'
        );
        $ddmMassAction->setAttribute('id', 'actionDeleted');
        $this->dataGrid->setMassAction($ddmMassAction);
        $this->frm->add($ddmMassAction);
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

        $url = BackendModel::getURLForBlock($this->URL->getModule(), 'Detail');
        $url404 = BackendModel::getURL(404);

        // parse additional variables
        if ($url404 != $url) {
            $this->tpl->assign('detailURL', SITE_URL . $url);
        }

        // assign
        $this->tpl->assign('item', $this->record);
        $this->tpl->assign('dataGrid', ($this->dataGrid->getNumResults() != 0) ? $this->dataGrid->getContent() : false);
        $this->tpl->assign('categories', $this->categories);
        $this->tpl->assign('imageCount', BackendSlideshowModel::getImagesByGallery($this->id));

        // settings allowed?
        $this->tpl->assign('settingsPerSlideshow', BackendModel::getModuleSetting('Slideshow', 'settings_per_slide'));
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
            $this->frm->getField('publish_on_date')->isValid(BL::err('DateIsInvalid'));
            $this->frm->getField('publish_on_time')->isValid(BL::err('TimeIsInvalid'));

            if ($this->frm->getField('width')->isFilled(BL::err('WidthIsRequired')))
            {
                $this->frm->getField('width')->isNumeric(BL::err('NumericCharactersOnly'));
            }

            if ($this->frm->getField('height')->isFilled())
            {
                $this->frm->getField('height')->isNumeric(BL::err('NumericCharactersOnly'));
            }

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
                };
            }

            $this->frm->getField('categories')->isFilled(BL::err('CategoryIsRequired'));
            $this->meta->validate();

            // no errors?
            if ($this->frm->isCorrect()) {
                //build settings item
                $settings['animation'] =
                $this->frm->getField('animation')->getValue();
                $settings['direction'] =
                $this->frm->getField('direction')->getValue();
                $settings['slideshow_speed'] =
                $this->frm->getField('slideshow_speed')->getValue();
                $settings['animation_speed'] =
                $this->frm->getField('animation_speed')->getValue();
                $settings['direction_navigation'] =
                $this->frm->getField('direction_navigation')->getChecked()? 'true' : 'false';
                $settings['control_navigation'] =
                $this->frm->getField('control_navigation')->getChecked() ? 'true' : 'false';
                $settings['thumbnail_navigation'] =
                $this->frm->getField('thumbnail_navigation')->getChecked() ? 'true' : '';
                $settings['keyboard'] =
                $this->frm->getField('keyboard')->getChecked() ? 'true' : 'false';
                $settings['mousewheel'] =
                $this->frm->getField('mousewheel')->getChecked() ? 'true' : 'false';
                $settings['touch'] =
                $this->frm->getField('touch')->getChecked() ? 'true' : 'false';
                $settings['randomize'] =
                $this->frm->getField('randomize')->getChecked() ? 'true' : 'false';
                $settings['auto_animate'] =
                $this->frm->getField('auto_animate')->getChecked() ? 'true' : 'false';
                $settings['animation_loop'] =
                $this->frm->getField('animation_loop')->getChecked() ? 'true' : 'false';

                // update settings
                BackendSlideshowModel::setSettings($this->id, $settings);

                // build item
                $item['id'] = $this->id;
                $item['meta_id'] = $this->meta->save(true);
                $item['language'] = $this->record['language'];
                $item['category_id'] = $this->frm->getField('categories')->getValue();
                $item['title'] = $this->frm->getField('title')->getValue();
                $item['description'] = $this->frm->getField('description')->getValue(true);
                $item['width'] = $this->frm->getField('width')->getValue();
                $item['height'] = $this->frm->getField('height')->getValue();

                // set height to null if empty
                if (empty($item['height'])) {
                    $item['height'] = null;
                }

                $item['publish_on'] = BackendModel::getUTCDate(
                    null,
                    BackendModel::getUTCTimestamp(
                        $this->frm->getField('publish_on_date'),
                        $this->frm->getField('publish_on_time')
                    )
                );
                $item['edited_on'] = BackendModel::getUTCDate();
                $item['hidden'] = $this->frm->getField('hidden')->getValue();

                // check if the category was changed
                if (!BackendSlideshowModel::getChangeCategory($this->id, $item['category_id'])) {
                    // if so, adjust the sequence to the new category
                    $item['sequence'] = BackendSlideshowModel::getMaximumGallerySequence(
                        $this->frm->getField('categories')->getValue()
                    )
                    + 1;
                }

                // if the image should be deleted
                if ($this->frm->getField('delete_image')->isChecked()) {
                    $fs = new Filesystem();

                    // delete the image
                    $fs->remove(
                        FRONTEND_FILES_PATH .
                        '/slideshow/' .
                        $this->record['filename']
                    );

                    //delete thumbnail
                    $fs->remove(
                        FRONTEND_FILES_PATH .
                        '/slideshow/thumbnails/' .
                        $this->record['filename']
                    );

                    // reset the name
                    $item['filename'] = null;
                }

                if ($this->frm->getField('filename')->isFilled()) {
                    // only delete the picture when there is one allready
                    if (!empty($this->record['filename'])) {
                        $fs = new Filesystem();

                        // delete the image
                        $fs->remove(
                            FRONTEND_FILES_PATH .
                            '/slideshow/' .
                            $this->record['filename']
                        );

                        //delete thumbnail
                        $fs->remove(
                            FRONTEND_FILES_PATH .
                            '/slideshow/thumbnails/' .
                            $this->record['filename']
                        );
                    }

                    // create new filename
                    $filename = $this->meta->getURL();
                    $filename .= '-' . uniqid();
                    $filename .= '-' . BL::getWorkingLanguage();
                    $filename .= '.' . $this->frm->getField('filename')->getExtension();

                    // add filename to item
                    $item['filename'] = $filename;

                    // create thumbnail
                    $this->frm->getField('filename')->createThumbnail(
                        FRONTEND_FILES_PATH . '/slideshow/thumbnails/' . $filename,
                        100,
                        100,
                        false,
                        false,
                        100
                    );

                    // @todo fix this
                    $this->frm->getField('filename')->moveFile(
                        FRONTEND_FILES_PATH .
                        '/slideshow/' .
                        $filename
                    );
                }

                // update gallery values in database
                BackendSlideshowModel::updateGallery($item);

                // trigger event
                BackendModel::triggerEvent(
                    $this->getModule(),
                    'after_edit',
                    array('item' => $item)
                );

                // get the gallery data
                $item = BackendSlideshowModel::getGallery($this->id);

                // trace the action and get the ids
                $action = $this->frm->getField('action')->getValue();
                $ids = (array) $_POST['id'];

                // Mass image delete action
                if ($action == 'delete') {
                    foreach ($ids as $id) {
                        // double check if the image exists
                        if ($id !== null && BackendSlideshowModel::existsImage($id)) {
                            // get item
                            $this->record = BackendSlideshowModel::getImage($id);

                            $fs = new Filesystem();

                            // delete the image and thumbnail
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

                            // delete item
                            BackendSlideshowModel::deleteImage($this->record['id']);
                        }
                    }
                    // redirect to edit, tab "images
                    $this->redirect(
                        BackendModel::createURLForAction('Edit') .
                        '&report=deleted&id=' .
                        $this->id .
                        '#images'
                    );
                } elseif ($action == 'publish') {
                    // set new status
                    BackendSlideshowModel::updatePublishedImage($ids);

                    // redirect to edit, tab #images
                    $this->redirect(
                        BackendModel::createURLForAction('Edit') .
                        '&report=saved&id=' .
                        $this->id .
                        '#images'
                    );
                } elseif ($action == 'hide') {
                    // set new status
                    BackendSlideshowModel::updateHiddenImage($ids);

                    // redirect to edit, tab #images
                    $this->redirect(
                        BackendModel::createURLForAction('Edit') .
                        '&report=saved&id=' .
                        $this->id .
                        '#images'
                    );
                }

                // everything is saved, so redirect to the overview
                $this->redirect(
                    BackendModel::createURLForAction('Index') .
                    '&report=saved&var=' .
                    urlencode($item['title']) .
                    '&highlight=' .
                    $item['id']
                );
            }
        }
    }
}
