<?php
class Core_Validate_CheckPlan extends Zend_Validate_Abstract {
	const ERROR = 'error';
	const INVALID = 'invalid';
	const INVALIDFORMAT = 'invalidformat';
	protected $_messageTemplates = array (self::ERROR => "'%value%' Should Not Match with Current Agency", self::INVALIDFORMAT => "'%value%' Invalid Format", self::INVALID => "'%value%' Invalid Pricing Plan" );
	
	
	/**
	 * @var string
	 */
	protected $_field = 'agency';
	
	public function __construct($options) {
	
		if ($options instanceof Zend_Config) {
			$options = $options->toArray ();
		} else if (func_num_args () > 1) {
			$options = func_get_args ();
			$temp ['field'] = array_shift ( $options );
			$options = $temp;
		}
	
		
		if (array_key_exists ( 'field', $options )) {
	
			$this->setField ( $options ['field'] );
		}
	
		
	}
	
	/**
	 * Returns the set field
	 *
	 * @return string|array
	 */
	public function getField() {
		return $this->_field;
	}
	
	/**
	 * Sets a new field
	 *
	 * @param string $field
	 * @return Zend_Validate_Db_Abstract
	 */
	public function setField($field) {
		$this->_field = ( string ) $field;
		return $this;
	}
	
	
	
	protected function _query($value, $agency) {
	
		
		$agencies = Api_Model_Service::getPlans ( $agency );
		
		if (in_array ( $value, $agencies )) {
			return true;
		}
		return false;
	}
	
	public function isValid($value, $context = null) {
		
		$this->_setValue ( $value );
		$isValid = true;
		
		$agency = $context [$this->_field];
		if (preg_match ( '/\(([^\)]+)\)/', $agency, $matches )) {
			$agency = $matches [1];
		}
		if (preg_match ( '/\(([^\)]+)\)/', $value, $matches )) {
			
			if (! $this->_query ( $matches [1], $agency )) {
				$this->_error ( self::INVALID );
				$isValid = false;
			}
		
		} else {
			$this->_error ( self::INVALIDFORMAT );
			$isValid = false;
		}
		return $isValid;
	}
}