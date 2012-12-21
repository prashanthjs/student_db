<?php

namespace Entities\Entity;

/**
 * User
 *
 * @Table(name="user")
 * @Entity @HasLifecycleCallbacks
 */
class User
{
    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $username
     *
     * @Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @var string $password
     *
     * @Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var datetime $createdDate
     *
     * @Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var datetime $updatedDate
     *
     * @Column(name="updated_date", type="datetime", nullable=true)
     */
    private $updatedDate;
    
    public function __get ($property)
    {
    
    	if (\method_exists($this, "get" .\ucfirst($property))) {
    		$method_name = "get" .\ucfirst($property);
    		return $this->$method_name();
    	} else
    		return $this->$property;
    }
    
    public function __set ($property, $value)
    {
    	if (\method_exists($this, "set" .\ucfirst($property))) {
    		$method_name = "set" .\ucfirst($property);
    
    		$this->$method_name($value);
    	} else
    		$this->$property = $value;
    }
    
    public function getModel ($id, $model)
    {
    	if (! is_object($id)) {
    		$em = \Zend_Registry::get('doctrine')->getEntityManager();
    		$model = $em->find($model, $id);
    		if ($model) {
    			return $model;
    		} else {
    			return false;
    		}
    	} else {
    		return $id;
    	}
    }
    
    /**
     * @PrePersist
     * @PreUpdate
     */
    public function setUpdateDate ()
    {
    	 
    	if (! $this->id) {
    		$this->createdDate = new \DateTime();
    
    	}
    	$this->updatedDate = new \DateTime();
    
    }

}