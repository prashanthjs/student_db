<?php

class Core_Validate_Doctrine_CheckByName extends Core_Validate_Doctrine_Abstract {
	
	
	protected $entity;
	
	public function __construct($options = null) {
		
		parent::__construct ( $options );
		
		if (array_key_exists ( 'entity', $options )) {
			
			$this->setEntity ( $options ['entity'] );
		}
		
	}
	
	public function setEntity($value) {
	
		$this->entity = $value;
	}
	
	public function getEntity() {
	
		return $this->entity ;
	}
	
	
	
	
	
	/**
	 * Run query and returns matches, or null if no matches are found.
	 *
	 * @param  String $value
	 * @return Array when matches are found.
	 */
	protected function _query($value) {
		$em = $this->_getAdapter ();
		
			$dql = "select u from " .  $this->getEntity () . ' u ';
		
		$dql = $dql . '   where u.name ' . " like '" . $value . "'";
		
		
		
		$query = $em->createQuery ( $dql );
		
		$result = $query->getResult ();
		
		if (! $result) {
			return false;
		}
		

// 		echo array_pop($result)->id;
// 		exit;
		$this->_setValue(array_pop($result)->id);
		
		return true;
	
	}
	
	public function isValid($value, $context = null) {
		
			$valid = true;
			$this->_setValue($value);
			
		
		if(!$value){
			$this->_error ( self::ERROR_RECORD_FOUND );
		}
		
		if(!is_int($value)){

			$result = $this->_query ( $value);
			
			if (!$result) {
				$valid = false;
				$this->_error ( self::ERROR_RECORD_FOUND );
			}
			
		}
		
		return $valid;
	
	}
}
