<?php

class Login_LoginController extends Core_Controller_Action {
	
	protected $_form = 'Login_Form_Login';
	
	public function init() {
		$this->getHelper ( 'layout' )->setLayout ( 'login' );
	}
	
	public function indexAction() {
		$this->view->loginForm = new $this->_form ();
		
		if ($this->getRequest ()->isPost ()) {
			
			if ($this->view->loginForm->isValid ( $this->getRequest ()->getPost () )) {
				
				$authAdapter = new Core_Auth_Adapter ( $this->view->loginForm->getValue ( 'email' ), $this->view->loginForm->getValue ( 'password' ) );
				
				$auth = Zend_Auth::getInstance ();
				$result = $auth->authenticate ( $authAdapter );
				if ($result->isValid ()) {
					$this->redirect ( array ('success' => 'Successfully Logged In' ) , $this->view->baseUrl () );
				}
				else{
					$this->setMessage ( array ('error' => 'Invalid Username or Password' ) );
				}
			
			} else {
				$this->setMessage ( array ('error' => 'Invalid Username or Password' ) );
			}
		}
		if(Core_Constants::getUser()){
		$this->_redirect('default/index/index');	
		}
	}
	
	public function logoutAction(){
		Zend_Auth::getInstance()->clearIdentity();
		$this->_helper->redirector->gotoUrl ('login/login/index' );
	}

}