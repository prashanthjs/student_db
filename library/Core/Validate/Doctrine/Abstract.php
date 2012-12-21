<?php

abstract class Core_Validate_Doctrine_Abstract extends Zend_Validate_Abstract {
	/**
	 * Error constants
	 */
	const ERROR_NO_RECORD_FOUND = 'noRecordFound';
	const ERROR_RECORD_FOUND = 'recordFound';
	
	/**
	 * @var array Message templates
	 */
	protected $_messageTemplates = array (self::ERROR_NO_RECORD_FOUND => "No record matching '%value%' was found", self::ERROR_RECORD_FOUND => "A record matching '%value%' was found" );
	
	/**
	 * @var string
	 */
	protected $_entity = '';
	
	/**
	 * @var string
	 */
	protected $_field = '';
	
	public function __construct($options) {
		
		if ($options instanceof Zend_Config) {
			$options = $options->toArray ();
		} else if (func_num_args () > 1) {
			$options = func_get_args ();
			$temp ['entity'] = array_shift ( $options );
			$temp ['field'] = array_shift ( $options );
			$options = $temp;
		}
		
		if (! array_key_exists ( 'entity', $options )) {
			require_once 'Zend/Validate/Exception.php';
			throw new Zend_Validate_Exception ( 'Table or Schema option missing!' );
		}
		
		if (array_key_exists ( 'field', $options )) {
			
			$this->setField ( $options ['field'] );
		}
		
		$this->setEntity ( $options ['entity'] );
	
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
	
	/**
	 * Returns the set table
	 *
	 * @return string
	 */
	public function getEntity() {
		return $this->_entity;
	}
	
	/**
	 * Sets a new table
	 *
	 * @param string $table
	 * @return Zend_Validate_Db_Abstract
	 */
	public function setEntity($entity) {
		$this->_entity = ( string ) $entity;
		return $this;
	}
	
	/**
	 * Run query and returns matches, or null if no matches are found.
	 *
	 * @param  String $value
	 * @return Array when matches are found.
	 */
	protected function _query($value) {
		$em = $this->_getAdapter ();
		$dql = ' select count(u) from ' . $this->getEntity () . ' u where u.' . $this->getField () . " = '" . $value . "'";
		
		$query = $em->createQuery ( ' select count(u) from ' . $this->getEntity () . ' u where u.' . $this->getField () . " = '" . $value . "'" );
		
		$result = $query->getSingleScalarResult ();
		return $result;
	}
	
	protected function _getAdapter() {
		$em = Zend_Registry::get ( 'doctrine' )->getEntityManager ();
		return $em;
	}
}
