<?php

class Admin_Form_Unit extends Core_Form_Main {
	
	protected $_request;
	protected $_model = 'Entities\Entity\Unit';
	
	public function init() {
		
		$name = new Zend_Form_Element_Text ( 'name' );
		$name->setLabel ( 'Unit Name' );
		$name->setRequired ( true );
		$name->addValidator ( 'NotEmpty', true );
		$name->addFilter ( 'HtmlEntities' );
		$name->addFilter ( 'StringTrim' );
		
			
		$submit = new Zend_Form_Element_Submit ( 'submit' );
		
		$this->addElements ( array ($name,	$submit ) );
		parent::init ();
	
	}

}

