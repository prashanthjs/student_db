<?php

class Admin_Form_Student extends Core_Form_Main {
	
	protected $_request;
	protected $_model = 'Entities\Entity\Student';
	
	public function init() {
		
		$firstName = new Zend_Form_Element_Text ( 'firstName' );
		$firstName->setLabel ( 'First Name' );
		$firstName->setRequired ( true );
		$firstName->addValidator ( 'NotEmpty', true );
		$firstName->addFilter ( 'HtmlEntities' );
		$firstName->addFilter ( 'StringTrim' );
		
		$lastName = new Zend_Form_Element_Text ( 'lastName' );
		$lastName->setLabel ( 'Last Name' );
		$lastName->setRequired ( true );
		$lastName->addValidator ( 'NotEmpty', true );
		$lastName->addFilter ( 'HtmlEntities' );
		$lastName->addFilter ( 'StringTrim' );
		
		$dob = new ZendX_JQuery_Form_Element_DatePicker ( 'dob' );
		$dob->setLabel ( 'Date of Birth' );
		$dob->setRequired ( true );
		$dob->setJQueryParam ( 'dateFormat', 'dd/mm/yy' );
		$dob->setDescription ( 'dd/mm/yyyy' );
		$dob->addValidator ( new Zend_Validate_Date ( array ('format' => 'dd/mm/yyyy' ) ) );
		
		
		$country = new Zend_Form_Element_Text ( 'country' );
		$country->setLabel ( 'country' );
		$country->setRequired ( true );
		$country->addValidator ( 'NotEmpty', true );
		$country->addFilter ( 'HtmlEntities' );
		$country->addFilter ( 'StringTrim' );
		
		$termAddress = new Zend_Form_Element_Text ( 'termAddress' );
		$termAddress->setLabel ( 'Term Address' );
		$termAddress->setRequired ( true );
		$termAddress->addValidator ( 'NotEmpty', true );
		$termAddress->addFilter ( 'HtmlEntities' );
		$termAddress->addFilter ( 'StringTrim' );
		
		$homeAddress = new Zend_Form_Element_Text ( 'homeAddress' );
		$homeAddress->setLabel ( 'Home Address' );
		$homeAddress->setRequired ( true );
		$homeAddress->addValidator ( 'NotEmpty', true );
		$homeAddress->addFilter ( 'HtmlEntities' );
		$homeAddress->addFilter ( 'StringTrim' );
		
		
		$level = new Zend_Form_Element_Text ( 'level' );
		$level->setLabel ( 'Level' );
		$level->setRequired ( true );
		$level->addValidator ( 'NotEmpty', true );
		$level->addFilter ( 'HtmlEntities' );
		$level->addFilter ( 'StringTrim' );
		
		$course = new Zend_Form_Element_Multiselect ( 'course' );
		$course->setLabel ( 'Course' );
		$course->setMultiOptions ( $this->getRecords ( '\Entities\Entity\Course' ) );
		$course->setRequired(true);
		
		
		$unit = new Zend_Form_Element_MultiSelect ( 'unit' );
		$unit->setLabel ( 'Unit' );
		$unit->setMultiOptions ( $this->getRecords ( '\Entities\Entity\Unit' ) );
		$unit->setRequired(true);
	
		
		$submit = new Zend_Form_Element_Submit ( 'submit' );
		
		$this->addElements ( array ($firstName, 
				$lastName, 
				$dob, 
				$homeAddress, 
				$termAddress, 
				$level,
				$course, 
				$unit,  
				$submit ) );
		parent::init ();
	
	}

}

