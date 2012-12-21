<?php

class Login_Form_Login extends Core_Form_Main {
	
	protected $_request;
	protected $_model = '';
	
	// 	<div class="input placeholder">
	// 	<label for="login" style="opacity: 1; ">Login</label>
	// 	<input type="text" id="login">
	// 	</div>
	

	protected $_elementDecorators = array (array ('ViewHelper' ), array ('Label', array ('class' => '' ) ), //  array('Errors'),
array (array ('fix' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'input placeholder' ) ) );
	
	protected $_submitDecorators = array (array ('ViewHelper' ), array (array ('fix' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'submit' ) ) );
	
	protected $_formDecorators = array ('FormElements', 'Form' );
	
	public function init() {
		
		$email = new Zend_Form_Element_Text ( 'email' );
		$email->setLabel ( 'Email' );
		$email->addFilter ( 'HtmlEntities' );
		$email->addFilter ( 'StringTrim' );
		
		$password = new Zend_Form_Element_Password ( 'password' );
		$password->setLabel ( 'Password' );
		$password->addFilter ( 'HtmlEntities' );
		$password->addFilter ( 'StringTrim' );
		
		$submit = new Zend_Form_Element_Submit ( 'Sign in' );
		
		$this->addElements ( array ($email, $password, $submit ) );
		parent::init ();
	
		$this->setElementDecorators($this->_elementDecorators);
		$submit->setDecorators($this->_submitDecorators);
		
		$this->setDecorators($this->_formDecorators);
		
	}
	public function addExtras(){
		
	}

}

