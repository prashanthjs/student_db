<?php
class Core_Validate_CheckSurveyor extends Zend_Validate_Abstract {
	
	const INVALID = 'invalid';
	const INVALIDFORMAT = 'invalidformat';
	
	protected $_messageTemplates = array (self::INVALIDFORMAT => "'%value%' Invalid Format", self::INVALID => "'%value%' Invalid Surveyor Email" );
	
	protected function _query($value) {
		$agencies = Api_Model_Service::getAgencies (null,true);
		$user = User_Model_SurveyorService::getSuveyorByEmail ( $value );
		
		if ($user) {
		
			if ($user->agency  && in_array ( $user->agency->id, $agencies )) {

				return true;
			}
		
		}
		
		return false;
	}
	
	public function isValid($value, $context = null) {
		$this->_setValue ( $value );
		$isValid = true;
		if (preg_match ( '/\(([^\)]+)\)/', $value, $matches )) {
			if (! $this->_query ( $matches [1] )) {
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