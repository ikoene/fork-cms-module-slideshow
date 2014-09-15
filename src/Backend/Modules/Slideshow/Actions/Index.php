<?php

namespace Backend\Modules\Slideshow\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\DataGridDB as BackendDataGridDB;
use Backend\Core\Engine\DataGridFunctions as BackendDataGridFunctions;
use Backend\Modules\Slideshow\Engine\Model as BackendSlideshowModel;

/**
 * This is the index-action (default), it will display the overview of slideshows
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class Index extends BackendBaseActionIndex
{
    /**
     * The datagrids
     *
     * @var array
     */
    private $dataGrids;


    /**
     * Execute the action
     *
     * @return  void
     */
    public function execute()
    {
        // call parent, this will probably add some general CSS/JS or other required files
        parent::execute();

        // load the datagrids
        $this->loadDataGrids();

        // parse the datagrids
        $this->parse();

        // display the page
        $this->display();
    }


    /**
     * Load the datagrids
     *
     * @return  void
     */
    private function loadDataGrids()
    {
        // load all categories that are in use
        $categories = BackendSlideshowModel::getActiveCategories(true);

        // run over categories and create datagrid for each one
        foreach ($categories as $categoryId => $categoryTitle) {
            // create datagrid
            $dataGrid = new BackendDataGridDB(
                BackendSlideshowModel::QRY_DATAGRID_BROWSE,
                array(BL::getWorkingLanguage(), $categoryId)
            );

            // disable paging
            $dataGrid->setPaging(false);

            // set colum URLs
            $dataGrid->setColumnURL(
                'title',
                BackendModel::createURLForAction('edit') . '&amp;id=[id]'
            );

            // set column functions
            $dataGrid->setColumnFunction(
                array(new BackendDataGridFunctions(), 'getLongDate'),
                array('[publish_on]'),
                'publish_on',
                true
            );
            $dataGrid->setColumnFunction(
                array(new BackendDataGridFunctions(), 'getUser'),
                array('[user_id]'),
                'user_id',
                true
            );

            // set headers
            $dataGrid->setHeaderLabels(
                array(
                    'user_id' => \SpoonFilter::ucfirst(BL::lbl('Author')),
                    'publish_on' => \SpoonFilter::ucfirst(BL::lbl('PublishedOn'))
                )
            );

            // enable drag and drop
            $dataGrid->enableSequenceByDragAndDrop();

            // our JS needs to know an id, so we can send the new order
            $dataGrid->setRowAttributes(array('id' => '[id]'));
            $dataGrid->setAttributes(array('data-action' => "GallerySequence"));

            // create a column #images
            $dataGrid->addColumn('images', BL::lbl('Images'));
            $dataGrid->setColumnFunction(
                array('Backend\Modules\Slideshow\Engine\Model', 'getImagesByGallery'),
                '[id]',
                'images',
                true
            );

            // hide columns
            $dataGrid->setColumnsHidden(array('category_id', 'sequence','filename'));

            // add manage images column
            $dataGrid->addColumn('imagesLink', null, BL::lbl('imagesLink'));
            $dataGrid->setColumnFunction(
                array(__CLASS__, 'setImagesLink'),
                array('[id]'),
                'imagesLink'
            );
            $dataGrid->setColumnAttributes('imagesLink', array('style' => 'width: 1%;'));

            // add edit column
            $dataGrid->addColumn(
                'edit',
                null,
                BL::lbl('Edit'),
                BackendModel::createURLForAction('edit') . '&amp;id=[id]',
                BL::lbl('Edit')
            );

            // set column order
            $dataGrid->setColumnsSequence(
                'dragAndDropHandle',
                'title',
                'publish_on',
                'user_id',
                'images',
                'imagesLink',
                'edit'
            );

            // add dataGrid to list
            $this->dataGrids[] = array(
                'id' => $categoryId,
                'title' => $categoryTitle,
                'content' => $dataGrid->getContent()
            );
        }
    }

    /**
     * Parse the datagrids and the reports
     *
     * @return  void
     */
    protected function parse()
    {
        // parse datagrids
        if (!empty($this->dataGrids)) {
            $this->tpl->assign('dataGrids', $this->dataGrids);
        }
    }

    /**
     * Datagrid method, sets a link to the images overview for the slideshow if
     * a module was not specified
     *
     * @param string $module The module string (which shouldn't be an empty one).
     * @param int $id The slideshow ID used in the URL parameters.
     * @return string
     */
    public static function setImagesLink($id)
    {
        $imagesLink = BackendModel::createURLForAction('edit') . '&amp;id=' . $id . '#images';

        return '<a class="button icon iconEdit linkButton" href="' . $imagesLink . '">
                    <span>' . BL::lbl('ManageImages') . '</span>
                </a>';
    }

}
