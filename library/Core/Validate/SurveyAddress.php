<?php
class Core_Validate_SurveyAddress extends Zend_Validate_Abstract {
	
	const INVALID = 'invalid';
	protected $field = 'postcode';
	
	protected $_messageTemplates = array (self::INVALID => "'%value%' already exists in database" );
	
	
	public function __construct($options = null) {
	
	//	parent::__construct ( $options );
	
		if (array_key_exists ( 'field', $options )) {
	
			$this->setField ( $options ['field'] );
		}
	
	}
	
	public function setField($value) {
	
		$this->field = $value;
	}
	
	public function getField() {
	
		return $this->field ;
	}
	
	protected function _query($hno, $postcode, $id) {
		$em = Zend_Registry::get ( 'doctrine' )->getEntityManager ();
		if ($id) {
			$survey = $em->find ( 'Entities\\Entity\\Survey', $id );
			$postcode1 = preg_replace ( '/\s+/', '', $survey->postcode );
			
			if ($survey && $survey->hno == $hno && strtolower($postcode1) == strtolower($postcode)) {
				
				return false;
			} else {
				return $this->getSurveyAddress ( $hno, $postcode );
			}
		}
		else{
			return $this->getSurveyAddress ( $hno, $postcode );
		}
		
		return true;
	}
	
	public function isValid($value, $context = null) {
		$this->_setValue ( $value );
		$isValid = true;
		
		$postcode = $context [$this->getField()];
		$postcode = preg_replace ( '/\s+/', '', $postcode );
		//$postcode = str_replace(' ', '', $postcode);
		
		
		//echo $postcode;
		$id = $context['id'];
		//echo $id;
		//exit;
		
		if ( $this->_query ( $value, $postcode, $id )) {
			$this->_error ( self::INVALID );
			$isValid = false;
		}
		
		return $isValid;
	}
	
	protected function getSurveyAddress($hno, $postcode) {
		$em = Zend_Registry::get ( 'doctrine' )->getEntityManager ();
		$dql = 'select count(s) from Entities\\Entity\\Survey s where s.postcode  like \'' . $postcode . "' and s.hno = '" . $hno . "'";
		
		$query = $em->createQuery ( $dql );
		return $query->getSingleScalarResult ();
		
	
	}
}