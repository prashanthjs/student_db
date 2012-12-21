<?php

/** 
 * @author Prashanth Pratapagiri
 * 
 * 
 */
class Core_Controller_Grid extends Core_Controller_Action
{

    protected $_title = '';

    protected $_indexPath = 'index.phtml';

    protected $_showPath = 'show.phtml';

    protected $_enablePath = 'enable.phtml';

    protected $_disablePath = 'disable.phtml';

    protected $_deletePath = 'delete.phtml';

    public function init ()
    {
        $this->view->addScriptPath(APPLICATION_PATH . '/common');
        $this->view->title = $this->getTitle();
        $this->view->addScriptPath(APPLICATION_PATH . '/common');
        parent::init();
    }

    public function getTitle ()
    {
        return $this->_title;
    }

    public function getIndexPath ()
    {
        return $this->_indexPath;
    }

    public function getShowPath ()
    {
        return $this->_showPath;
    }

    public function getEnablePath ()
    {
        return $this->_enablePath;
    }

    public function getDisablePath ()
    {
        return $this->_disablePath;
    }

    public function getDeletePath ()
    {
        return $this->_deletePath;
    }

    public function indexAction ()
    {
        
        $model = $this->_model;
        $this->view->displayFields = $model::getDisplayFields();
        
        try {
            $this->view->render(
            $this->getRequest()
                ->getControllerName() . '/' . 'index.phtml');
        } catch (Exception $e) {
            $this->renderScript($this->getIndexPath());
        }
    
    }

    public function showAction ()
    {
        // action body
        $this->_helper->layout->disableLayout();
        $model = $this->_model;
        $pageNo = $this->view->pageNo = $this->_getParam('page', 1);
        $perPage = $this->view->perPage = $this->_getParam('pageSize', 10);
        $sort = $this->_getParam('sort', array());
        $filter = $this->_getParam('filter', array());
        $group = $this->_getParam('group', array());
        
        $results = $model::getResults($pageNo, $perPage, $sort, $filter, $group);
        if (! $results || ! isset($results['results'])) {
            $results['results'] = array();
        }
        $parsedResults = $model::parseResults($results['results']);
        header('Content-type: application/json');
        $this->view->json = array(
            'totals' => (int) $results['total'], 
            'data' => $parsedResults);
        //$this->renderScript('show.phtml');
        

        try {
            $this->view->render(
            $this->getRequest()
                ->getControllerName() . '/' . 'show.phtml');
        } catch (Exception $e) {
            $this->renderScript($this->getShowPath());
        }
    
    }

    /**
     *
     */
    public function deleteAction ()
    {
        $id = $this->_getParam('id', '');
        $model = $this->_model;
        try {
            $model = $model::delete($id);
            $this->view->javascriptRedirection = true;
        
        } catch (Exception $e) {
            $this->setMessage(array('error' => 'Cannot be Deleted'));
            
        }
        try {
            $this->view->render(
                    $this->getRequest()
                    ->getControllerName() . '/' . 'delete.phtml');
        } catch (Exception $e) {
            $this->renderScript($this->getDeletePath());
        }
    }

    public function enableAction ()
    {
        $id = $this->_getParam('id', '');
        $model = $this->_model;
        try {
            $model = $model::enable($id);
            $this->view->javascriptRedirection = true;
        
     //  exit;
        

        } catch (Exception $e) {
            $this->setMessage(array('error' => 'Cannot be Enabled'));
        }
        try {
            $this->view->render(
            $this->getRequest()
                ->getControllerName() . '/' . 'enable.phtml');
        } catch (Exception $e) {
            $this->renderScript($this->getEnablePath());
        }
    }

    public function disableAction ()
    {
        $id = $this->_getParam('id', '');
        $model = $this->_model;
        
        try {
            $model = $model::disable($id);
            $this->view->javascriptRedirection = true;
        } catch (Exception $e) {
            $this->setMessage(array('error' => 'Cannot be Disabled'));
        }
        try {
            $this->view->render ( $this->getRequest()->getControllerName() . '/'. 'disable.phtml' );
        } catch ( Exception $e ) {
            $this->renderScript($this->getDisablePath());
        }
    }

}
