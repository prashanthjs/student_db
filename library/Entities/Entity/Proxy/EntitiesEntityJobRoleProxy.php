<?php

namespace Entities\Entity\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class EntitiesEntityJobRoleProxy extends \Entities\Entity\JobRole implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    private function _load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    
    public function __get($property)
    {
        $this->_load();
        return parent::__get($property);
    }

    public function __set($property, $value)
    {
        $this->_load();
        return parent::__set($property, $value);
    }

    public function getModel($id, $model)
    {
        $this->_load();
        return parent::getModel($id, $model);
    }

    public function setUpdateDate()
    {
        $this->_load();
        return parent::setUpdateDate();
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'name', 'createdDate', 'updatedDate');
    }
}