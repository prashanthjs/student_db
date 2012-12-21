<?php

class Core_Validate_Doctrine_CheckUserName extends Core_Validate_Doctrine_Abstract {
	
	protected $relationship;
	protected $entityKey;
	
	public function __construct($options = null) {
		
		parent::__construct ( $options );
		
		if (array_key_exists ( 'relationship', $options )) {
			
			$this->setRelationship ( $options ['relationship'] );
		}
		
		if (array_key_exists ( 'entity_key', $options )) {
		
			$this->setEntityKey ( $options ['entity_key'] );
		}
	
	}
	
	public function setEntityKey($value) {
	
		$this->entityKey = $value;
	}
	
	public function getEntityKey() {
		return $this->entityKey;
	}
	
	public function setRelationship($value) {
		
		$this->relationship = $value;
	}
	
	public function getRelationship() {
		return $this->relationship;
	}
	
	/**
	 * Run query and returns matches, or null if no matches are found.
	 *
	 * @param  String $value
	 * @return Array when matches are found.
	 */
	protected function _query($value, $id = '') {
		$em = $this->_getAdapter ();
		
		if ($this->getRelationship ()) {
			$dql = "select u from Entities\\Entity\\" . ucfirst ( $this->getRelationship () ) . ' u ';
		
		} else {
			$dql = ' select u from ' . $this->getEntity () . ' u ';
		
		}
		$dql = $dql . '   where u.' . $this->getField () . " = '" . $value . "'";
		
		$query = $em->createQuery ( $dql );
		
		$result = $query->getResult ();
		
		if (! $result) {
			return false;
		}
		
		if ($id) {
			
			if ($this->getRelationship ()) {
				if ($result [0]->{$this->getEntityKey()}->id == $id) {
					return false;
				}
			} else {
				if ($result [0]->id == $id) {
					return false;
				}
			}
		
		}
		
		return true;
	
	}
	
	public function isValid($value, $context = null) {
		
		$valid = true;
		$this->_setValue ( $value );
		
		$result = $this->_query ( $value, $context ['id'] );
		if ($result) {
			$valid = false;
			$this->_error ( self::ERROR_RECORD_FOUND );
		}
		
		return $valid;
	
	}
}
