<?php

namespace Entities\Entity;

/**
 * Student
 *
 * @Table(name="student")
 * @Entity @HasLifecycleCallbacks
 */
class Student
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
     * @var string $firstName
     *
     * @Column(name="first_name", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var string $lastName
     *
     * @Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var date $dob
     *
     * @Column(name="dob", type="date", nullable=true)
     */
    private $dob;

    /**
     * @var string $country
     *
     * @Column(name="country", type="string", length=45, nullable=true)
     */
    private $country;

    /**
     * @var string $termAddress
     *
     * @Column(name="term_address", type="string", length=510, nullable=true)
     */
    private $termAddress;

    /**
     * @var string $homeAddress
     *
     * @Column(name="home_address", type="string", length=510, nullable=true)
     */
    private $homeAddress;
    
   

    /**
     * @var string $level
     *
     * @Column(name="level", type="string", length=45, nullable=true)
     */
    private $level;

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
    
   
    public function setDob ($value)
    {
    
    	if($value !== null){
    		$this->dob = \DateTime::createFromFormat('d/m/Y', $value);
    		
    	}
    	
    
    }
    
    public function getDob(){
    	if($this->dob){
    		return $this->dob->format('d/m/Y');
    	}
    }

}