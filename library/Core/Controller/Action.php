<?php

/** 
 * @author Prashanth Pratapagiri
 * 
 * 
 */
class Core_Controller_Action extends Zend_Controller_Action {
	
	/**
	 * 
	 * @var string
	 */
	protected $_createForm = 'User_Form_Create';
	/**
	 * 
	 * @var string
	 */
	protected $_searchForm = 'User_Form_Search';
	/**
	 * 
	 * @var string
	 */
	protected $_model = 'User_Model_Service';
	
	public function init(){
		if($this->isAjax()){
			$this->_helper->layout()->setLayout('ajax');
		}
	}
	
	public function isAjax(){
		if($this->_getParam('format') == 'ajax'){
			return true;
		}
		return false;
	}
	
	public function indexAction() {
		// action body
		$model = $this->_model;
		$this->view->form = new $this->_searchForm ();
		$pageNo = $this->view->pageNo = $this->_getParam ( 'page', 1 );
		$perPage = $this->view->perPage = $this->_getParam ( 'perPage', 30 );
		
		$this->view->users = $model::getResults ( $pageNo, $perPage );
		$this->view->form->populate ( $this->_getAllParams () );
	}
	protected function beforeSave($form) {
		
	
	}
	
	protected function afterSave($id, $form) {
	
	}
	
	public function createAction() {
		$this->view->form = new $this->_createForm ();
		
		$model = $this->_model;
		if ($this->getRequest ()->isPost ()) {
			if ($this->view->form->isValid ( $this->getRequest ()->getPost () )) {
				try {
					$this->beforeSave ( $this->view->form );

					$model = $model::save ( $this->view->form->getValues () );



					
					$this->afterSave ( $model, $this->view->form );
					$this->view->form->id->setValue($model->id);
					
					if($this->isAjax()){
					$this->setMessage( array ('success' => 'Successfully saved' ));
					$this->view->javascriptRedirection = "true";
					}
					else{
						$this->redirect( array ('success' => 'Successfully saved' ));
					}
				} catch ( Exception $e ) {


					$this->setMessage ( array ('error' => $e->getMessage () ) );
				}
			} else {
				$this->setMessage ( array ('error' => 'Please Correct The Errors' ) );
			}
		
		} else {
			
			$id = $this->_getParam ( 'id', '' );
			if ($id) {
				try {
				
					$values = $model::getRecordArray ( $id );
					$this->view->form->populate ( $values );
				} catch ( Exception $e ) {
					$this->setMessage ( array ('error' => $e->getMessage () ) );
				}
			}
		
		}
	
	}
	/**
	 *
	 */
	public function deleteAction() {
		$id = $this->_getParam ( 'id', '' );
		$model = $this->_model;
		try {
			$model = $model::delete ( $id );
			$this->redirect ( array ('success' => 'Deleted Successfully' ) );
		} catch ( Exception $e ) {
			$this->setMessage ( array ('error' => 'Cannot be Deleted' ) );
		}
	}
	
	public function enableAction() {
		$id = $this->_getParam ( 'id', '' );
		$model = $this->_model;
		try {
			$model = $model::enable ( $id );
			$this->redirect ( array ('success' => 'Enabled Successfully' ) );
		} catch ( Exception $e ) {
			$this->setMessage ( array ('error' => 'Cannot be Enabled' ) );
		}
	}
	
	public function disableAction() {
		$id = $this->_getParam ( 'id', '' );
		$model = $this->_model;
		try {
			$model = $model::disable ( $id );
			$this->redirect ( array ('success' => 'Disabled Successfully' ) );
		} catch ( Exception $e ) {

		
			$this->setMessage ( array ('error' => 'Cannot be Disabled' ) );
		}
	}
	
	/**
	 *
	 * @param string $message
	 * @param string $url
	 */
	public function redirect($message = '', $url = '') {
		$flashMessenger = $this->_helper->getHelper ( 'FlashMessenger' );
		$flashMessenger->addMessage ( $message );
		$redirector = $this->_getParam ( 'redirect_url' );
		if ($redirector == '') {
			if ($url == '')
				$redirector = $this->_getParam ( 'module' ) . '/' . $this->_getParam ( 'controller' ) . '/' . $this->_getParam ( 'action' );
			else
				$redirector = '';
		}
		$this->_helper->redirector->gotoUrl ( urldecode ( $redirector ) );
	}
	
	/**
	 *
	 * @param string $message
	 * 
	 */
	public function setMessage($message = '') {
		
		Zend_Layout::getMvcInstance ()->assign ( 'message', $message );
	
	}

}
