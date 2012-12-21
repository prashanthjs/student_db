<?php
class Core_Validate_CheckSurveyDate extends Zend_Validate_Abstract {
	
	const INVALIDFORMAT = 'invalidformat';
	
	protected $_messageTemplates = array ( self::INVALIDFORMAT => "Invalid Date" );
	
	protected function _query($value) {
		$array = explode(',', $value);
		//$timestamp = time;
		$date1 = new \DateTime();
		foreach($array as $avalue){
			$date2 = \DateTime::createFromFormat ( 'd/m/Y', $avalue );
			if($date1->getTimestamp() > $date2->getTimestamp()){
				return false;
			}
		}
		return true;
		
	}
	
	public function isValid($value, $context = null) {
		$isValid = true;
		$this->_setValue ( $value );
		$isValid = true;
		if(!$this->_query($value)) {
			$this->_error ( self::INVALIDFORMAT);
			$isValid = false;
		}
		return $isValid;
	}
}