<?php

namespace Backend\Modules\Slideshow\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\DataGridDB as BackendDataGridDB;
use Backend\Core\Engine\DataGridFunctions as BackendDataGridFunctions;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Slideshow\Engine\Model as BackendSlideshowModel;

/**
 * This is the categories-action, it will display the overview of slideshow categories
 *
 * @author Koen Vinken <koen@tagz.be>
 */
class Categories extends BackendBaseActionIndex
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

        // load datagrids
        $this->loadDataGrid();

        // parse page
        $this->parse();

        // display the page
        $this->display();
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
            BackendSlideshowModel::QRY_DATAGRID_BROWSE_CATEGORIES,
            BL::getWorkingLanguage()
        );

        // disable paging
        $this->dataGrid->setPaging(false);

        // set column URLs
        $this->dataGrid->setColumnURL(
            'title',
            BackendModel::createURLForAction('EditCategory') . '&amp;id=[id]'
        );

        // create a column #galleries
        $this->dataGrid->addColumn('galleries', BL::lbl('Galleries'));
        $this->dataGrid->setColumnFunction(
            array(
                'Backend\Modules\Slideshow\Engine\Model',
                'getGalleriesByCategory'),
            '[id]',
            'galleries',
            true
        );

        // enable drag and drop
        $this->dataGrid->enableSequenceByDragAndDrop();

        // our JS needs to know an id, so we can send the new order
        $this->dataGrid->setRowAttributes(array('id' => '[id]'));
        $this->dataGrid->setAttributes(array('data-action' => "CategorySequence"));

        // add edit column
        $this->dataGrid->addColumn(
            'edit',
            null,
            BL::lbl('Edit'),
            BackendModel::createURLForAction('EditCategory') . '&amp;id=[id]',
            BL::lbl('Edit')
        );
    }


    /**
     * Parse & display the page
     *
     * @return  void
     */
    protected function parse()
    {
        $this->tpl->assign(
            'dataGrid',
            ($this->dataGrid->getNumResults() != 0) ? $this->dataGrid->getContent() : false
        );
    }
}
