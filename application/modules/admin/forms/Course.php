<?php

class Admin_Form_Course extends Core_Form_Main {
	
	protected $_request;
	protected $_model = 'Entities\Entity\Course';
	
	public function init() {
		
		$name = new Zend_Form_Element_Text ( 'name' );
		$name->setLabel ( 'Course Name' );
		$name->setRequired ( true );
		$name->addValidator ( 'NotEmpty', true );
		$name->addFilter ( 'HtmlEntities' );
		$name->addFilter ( 'StringTrim' );
		
			
		$submit = new Zend_Form_Element_Submit ( 'submit' );
		
		$this->addElements ( array ($name,	$submit ) );
		parent::init ();
	
	}

}

