<?php

namespace Entities\Entity;

/**
 * StudentCourse
 *
 * @Table(name="student_course")
 * @Entity @HasLifecycleCallbacks
 */
class StudentCourse
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
     * @var Student
     *
     * @ManyToOne(targetEntity="Student")
     * @JoinColumns({
     *   @JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private $student;

    /**
     * @var Course
     *
     * @ManyToOne(targetEntity="Course")
     * @JoinColumns({
     *   @JoinColumn(name="course_id", referencedColumnName="id")
     * })
     */
    private $course;

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