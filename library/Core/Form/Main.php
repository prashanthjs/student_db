<?php

/** 
 * @author developer
 * 
 * 
 */
class Core_Form_Main extends Zend_Form {
	//TODO - Insert your code here
	

	protected $_em;
	protected $_request;
	protected $_model = '';
	
	public function __construct($options = null) {
		$this->_em = Zend_Registry::get ( 'doctrine' )->getEntityManager ();
		$this->addElementPrefixPath ( 'Core_Validate', 'Core/Validate', 'validate' );
		$this->addElementPrefixPath ( 'Core_Validate_Doctrine', 'Core/Validate/Doctrine', 'validate' );
		parent::__construct ( $options );
		
	//	$this->addDecorator(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element'));
	}
	
	public function init() {
		
		
		$this->addExtras ();
		$this->decorate();
	}
	
	
	public function decorate(){
		
		$elements = $this->getElements();
		
		foreach($elements as $element){
			
		}
		
	}
	
	
	public function addElement($element,$name=null, $options = null){
		$element->addDecorator(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'wrap-element'));
		parent::addElement($element,$name,$options);
	}
	
	public function addExtras() {
		$this->_request = Zend_Controller_Front::getInstance ()->getRequest ();
		
		$id = new Zend_Form_Element_Hidden ( 'id' );
		$value = $this->_request->getParam ( 'id', '' );
		if ($value) {
			$id->setValue ( $value );
			$id->addValidator ( 'RecordExists', true, array ('entity' => $this->_model, 'field' => 'id' ) );
		}
		$this->addElement ( $id );
		
		$redirect_url = new Zend_Form_Element_Hidden ( 'redirect_url' );
		$redirect_url->setValue ( urlencode ( $this->_request->getParam ( 'redirect_url', '' ) ) );
		$this->addElement ( $redirect_url );
	
	}
	
	public function getRecords($modelName, $selectString = '') {
		$query = $this->_em->createQuery ( 'Select u from ' . $modelName . ' u ' );
		$results = $query->getResult ();
		$array = array ();
		if ($selectString != '')
			$array [0] = $selectString;
		
		foreach ( $results as $result ) {
			
			$array [$result->id] = $result->name;
		}
		
		return $array;
	
	}
	
	public function getRoles($string = '') {
		$query = $this->_em->createQuery ( 'Select u from Entities\Entity\Role u  where u.id !='.Core_Constants::SUPER_POWER_ADMIN.' and u.id != '.Core_Constants::SURVEYOR. ' ' );
		$results = $query->getResult ();
		$array = array ();
		
		if($string)
			$array[0]=$string;
	
		foreach ( $results as $result ) {
	
			$array [$result->id] = $result->name;
		}
	
		return $array;
	
	}
	
	public function getBaseUrl(){
		return Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
	}
	
	public function getRequest(){
		return Zend_Controller_Front::getInstance()->getRequest();
	}
	
	
	
	
}