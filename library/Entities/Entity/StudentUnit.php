<?php

namespace Entities\Entity;

/**
 * StudentUnit
 *
 * @Table(name="student_unit")
 * @Entity @HasLifecycleCallbacks
 */
class StudentUnit
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

    /**
     * @var Unit
     *
     * @ManyToOne(targetEntity="Unit")
     * @JoinColumns({
     *   @JoinColumn(name="unit_id", referencedColumnName="id")
     * })
     */
    private $unit;

    /**
     * @var Student
     *
     * @ManyToOne(targetEntity="Student")
     * @JoinColumns({
     *   @JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private $student;
    
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