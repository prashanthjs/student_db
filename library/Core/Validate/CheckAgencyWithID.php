<?php
class Core_Validate_CheckAgencyWithID extends Zend_Validate_Abstract {
	const ERROR = 'error';
	const INVALID = 'invalid';
	const INVALIDFORMAT = 'invalidformat';	
	protected $_messageTemplates = array (self::ERROR => "'%value%' Should Not Match with Current Agency", self::INVALIDFORMAT => "'%value%' Invalid Format",
			self::INVALID => "'%value%' Invalid Agency ID"
			);
	
	protected function _query($value){
		$agencies = Api_Model_Service::getAgencies();
	
		if(in_array($value, $agencies)){
			return true;
		}
		return false;
	}
	
	public function isValid($value, $context = null) {
		$this->_setValue ( $value );
		$isValid = true;
		if (preg_match ( '/\(([^\)]+)\)/', $value, $matches )) {
			
			if ($context ['id'] == $matches [1]) {
				$this->_error ( self::ERROR );
				$isValid = false;
			}
			else{
				if(!$this->_query($matches [1])){
					$this->_error ( self::INVALID);
					$isValid = false;
				}
			}
		
		} else {
			$this->_error ( self::INVALIDFORMAT );
			$isValid = false;
		}
		return $isValid;
	}
}